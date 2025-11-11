<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class PayPalService {
    private $clientId;
    private $clientSecret;
    private $apiBase = 'https://api-m.sandbox.paypal.com'; // Sandbox URL

    public function __construct() {
        $instance = lava_instance();
        $payment_config = $instance->config->get('payment');

        if (isset($payment_config['paypal'])) {
            $config = $payment_config['paypal'];
        } else {
            throw new Exception('PayPal configuration not found');
        }

        $this->clientId = $config['client_id'];
        $this->clientSecret = $config['secret'];

        if (empty($this->clientId) || empty($this->clientSecret)) {
            throw new Exception('PayPal API credentials are not configured');
        }

        // Set to live if mode is live
        if (isset($config['mode']) && $config['mode'] === 'live') {
            $this->apiBase = 'https://api-m.paypal.com';
        }
    }

    private function getAccessToken() {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->apiBase . '/v1/oauth2/token');
        curl_setopt($ch, CURLOPT_USERPWD, $this->clientId . ':' . $this->clientSecret);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json', 'Accept-Language: en_US']);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            $err = curl_error($ch);
            curl_close($ch);
            throw new Exception('PayPal token request failed: ' . $err);
        }
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpcode !== 200) {
            throw new Exception('PayPal token request returned HTTP ' . $httpcode . ': ' . $result);
        }

        $json = json_decode($result, true);
        if (!isset($json['access_token'])) {
            throw new Exception('Invalid PayPal token response: ' . $result);
        }
        return $json['access_token'];
    }

    private function request($method, $endpoint, $data = null, $accessToken = null) {
        if (!$accessToken) {
            $accessToken = $this->getAccessToken();
        }

        $ch = curl_init();
        $url = $this->apiBase . $endpoint;

        $headers = [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json',
            'Accept: application/json'
        ];

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

        if ($data !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            $err = curl_error($ch);
            curl_close($ch);
            throw new Exception('PayPal request failed: ' . $err);
        }
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpcode >= 400) {
            throw new Exception('PayPal API error: HTTP ' . $httpcode . ' - ' . $response);
        }

        return json_decode($response, true);
    }

    public function createOrder($data) {
        $orderData = [
            'intent' => 'CAPTURE',
            'purchase_units' => [[
                'amount' => [
                    'currency_code' => $data['currency'] ?? 'USD',
                    'value' => number_format($data['amount'], 2, '.', '')
                ],
                'description' => $data['description'] ?? 'Payment'
            ]]
        ];

        return $this->request('POST', '/v2/checkout/orders', $orderData);
    }

    public function captureOrder($orderId) {
        return $this->request('POST', '/v2/checkout/orders/' . $orderId . '/capture');
    }

    public function getOrder($orderId) {
        return $this->request('GET', '/v2/checkout/orders/' . $orderId);
    }
}
