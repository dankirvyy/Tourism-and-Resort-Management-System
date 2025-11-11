<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Payment Gateway Configuration
|--------------------------------------------------------------------------
|
| This file contains all the configuration settings for payment gateways
|
*/

$config['payment_services'] = array(
    'paymongo' => array(
        'public_key' => getenv('PAYMONGO_PUBLIC_KEY') ?: 'your_paymongo_public_key_here',
        'secret_key' => getenv('PAYMONGO_SECRET_KEY') ?: 'your_paymongo_secret_key_here',
        'webhook_secret' => getenv('PAYMONGO_WEBHOOK_SECRET') ?: '', // Add your webhook secret if you have one
        'mode' => getenv('PAYMONGO_MODE') ?: 'test' // or 'live' for production
    )
);

// Default service to use
$config['default_payment_service'] = 'paymongo';

// Backwards-compatible single payment config used by controllers
$config['payment'] = array(
    'gateway' => 'paymongo', // default gateway: paymongo or paypal

    // PayPal sandbox settings (replace with your sandbox app credentials)
    'paypal' => array(
        'mode' => getenv('PAYPAL_MODE') ?: 'sandbox', // 'sandbox' or 'live'
        'client_id' => getenv('PAYPAL_CLIENT_ID') ?: 'your_paypal_client_id_here',
        'secret' => getenv('PAYPAL_SECRET') ?: 'your_paypal_secret_here'
    )
);

// Google reCAPTCHA v2 Configuration
$config['recaptcha'] = array(
    'site_key' => getenv('RECAPTCHA_SITE_KEY') ?: 'your_recaptcha_site_key_here', // Your reCAPTCHA site key (get from https://www.google.com/recaptcha/admin)
    'secret_key' => getenv('RECAPTCHA_SECRET_KEY') ?: 'your_recaptcha_secret_key_here' // Your reCAPTCHA secret key
);