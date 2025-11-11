<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * Verify reCAPTCHA response with Google's API
 * 
 * @param string $response The g-recaptcha-response token from the form
 * @return bool True if verification succeeds, false otherwise
 */
function verify_recaptcha($response) {
    $recaptcha_config = config_item('recaptcha');
    
    if (empty($recaptcha_config['secret_key'])) {
        error_log('reCAPTCHA Error: Secret key not configured');
        return false;
    }
    
    if (empty($response)) {
        error_log('reCAPTCHA Error: No response token provided');
        return false;
    }
    
    $secret_key = $recaptcha_config['secret_key'];
    $verify_url = 'https://www.google.com/recaptcha/api/siteverify';
    
    // Prepare POST data
    $data = array(
        'secret' => $secret_key,
        'response' => $response,
        'remoteip' => $_SERVER['REMOTE_ADDR']
    );
    
    // Use cURL to verify
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $verify_url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    
    $result = curl_exec($ch);
    
    if (curl_errno($ch)) {
        error_log('reCAPTCHA cURL Error: ' . curl_error($ch));
        curl_close($ch);
        return false;
    }
    
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpcode !== 200) {
        error_log('reCAPTCHA HTTP Error: ' . $httpcode);
        return false;
    }
    
    $json = json_decode($result, true);
    
    if (!$json) {
        error_log('reCAPTCHA JSON Error: Invalid response');
        return false;
    }
    
    // Log for debugging (remove in production)
    error_log('reCAPTCHA Response: ' . json_encode($json));
    
    // Check if verification was successful
    if (isset($json['success']) && $json['success'] === true) {
        return true;
    }
    
    // Log error codes if verification failed
    if (isset($json['error-codes'])) {
        error_log('reCAPTCHA Errors: ' . implode(', ', $json['error-codes']));
    }
    
    return false;
}
