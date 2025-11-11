<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class PaymongoService {
    private $secretKey;
    private $publicKey;
    private $apiBase = 'https://api.paymongo.com/v1';

    public function __construct() {
        $instance = lava_instance();
        $payment_config = $instance->config->get('payment');
        $payment_services_config = $instance->config->get('payment_services');

        // Check both locations for backwards compatibility
        if (isset($payment_services_config['paymongo'])) {
            $config = $payment_services_config['paymongo'];
        } elseif (isset($payment_config['paymongo'])) {
            $config = $payment_config['paymongo'];
        } else {
            throw new Exception('PayMongo configuration not found');
        }

        $this->secretKey = $config['secret_key'];
        $this->publicKey = $config['public_key'];

        if (empty($this->secretKey) || empty($this->publicKey)) {
            throw new Exception('PayMongo API keys are not configured');
        }
    }

    private function request($method, $endpoint, $data = null) {
        $ch = curl_init();
        $url = $this->apiBase . $endpoint;
        
        // Log request details for debugging
        error_log("PayMongo Request - Method: {$method}, URL: {$url}");
        
        $headers = [
            'Authorization: Basic ' . base64_encode($this->secretKey . ':'),
            'Content-Type: application/json',
            'Accept: application/json'
        ];

        error_log("PayMongo Request - Headers: " . json_encode($headers));

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        // For development environments it's sometimes necessary to disable SSL verification.
        // Keep these as-is for now but consider enabling in production.
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

        // If we have data, set the JSON payload once
        if ($data !== null) {
            $payload = ['data' => ['attributes' => $data]];
            $jsonPayload = json_encode($payload);
            error_log("PayMongo Request - Payload: " . $jsonPayload);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);
        }

        // Execute the request
        $response = curl_exec($ch);

        // Capture any cURL errors first
        if (curl_errno($ch)) {
            $curlError = curl_error($ch);
            error_log("PayMongo Error - cURL Error: " . $curlError);
            curl_close($ch);
            throw new Exception('PayMongo request failed: ' . $curlError);
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        error_log("PayMongo Response - HTTP Code: {$httpCode}");
        error_log("PayMongo Raw Response: " . $response);

        if ($response === false) {
            $error = curl_error($ch);
            error_log("PayMongo Error - cURL Error after exec: " . $error);
            curl_close($ch);
            throw new Exception('PayMongo API error: Failed to get response from server - ' . $error);
        }

        curl_close($ch);

        $responseData = json_decode($response, true);
        error_log("PayMongo Parsed Response: " . json_encode($responseData));
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log("PayMongo Error - JSON Parse Error: " . json_last_error_msg());
            throw new Exception('PayMongo API error: Invalid JSON response - ' . json_last_error_msg());
        }
        
        if ($httpCode >= 400) {
            $errorMessage = 'Unknown error occurred';
            if (isset($responseData['errors']) && is_array($responseData['errors'])) {
                foreach ($responseData['errors'] as $error) {
                    $errorMessage = isset($error['detail']) ? $error['detail'] : 'Unknown error';
                    $errorCode = isset($error['code']) ? $error['code'] : '';
                    error_log("PayMongo Error - API Error: {$errorMessage} [{$errorCode}]");
                }
            }
            throw new Exception("PayMongo API error: {$errorMessage} [{$errorCode}] (HTTP {$httpCode})");
        }
        
        if (!isset($responseData['data'])) {
            error_log("PayMongo Error - No Data in Response: " . json_encode($responseData));
            throw new Exception('PayMongo API error: Invalid response format - missing data');
        }
        
        return $responseData['data'];
    }

    public function createSource($data) {
        try {
            error_log("PayMongo - Creating source with data: " . json_encode($data));
            
            // Format the source data according to PayMongo's API
            $sourceData = [
                'type' => $data['type'],
                'amount' => $data['amount'],
                'currency' => $data['currency'],
                'redirect' => $data['redirect'],
                'billing' => $data['billing']
            ];
            
            $result = $this->request('POST', '/sources', $sourceData);
            error_log("PayMongo - Source created successfully: " . json_encode($result));
            return $result;
        } catch (Exception $e) {
            error_log("PayMongo - Error creating source: " . $e->getMessage());
            throw $e;
        }
    }

    public function retrieveSource($sourceId) {
        try {
            // Clean and validate the source ID
            $sourceId = trim($sourceId);
            if (empty($sourceId)) {
                throw new Exception('Invalid source ID provided');
            }
            
            error_log("PayMongo - Retrieving source with ID: " . $sourceId);
            error_log("PayMongo - Full URL: " . $this->apiBase . '/sources/' . $sourceId);
            
            // Make the GET request without a data payload
            $result = $this->request('GET', '/sources/' . $sourceId);
            
            error_log("PayMongo - Source retrieved successfully: " . json_encode($result));
            return $result;
        } catch (Exception $e) {
            error_log("PayMongo - Error in retrieveSource: " . $e->getMessage());
            throw $e;
        }
    }

    public function createPayment($data) {
        return $this->request('POST', '/payments', $data);
    }

    public function retrievePayment($paymentId) {
        return $this->request('GET', '/payments/' . $paymentId);
    }
}