<?php
/**
 * SSL Test Script - Tests if SSL verification works with system default CA bundle
 * Run this at: http://localhost/Tourism-and-Resort-Management-System/test_ssl.php
 */

echo "<h1>SSL Connection Test</h1>";
echo "<p>Testing if SSL works with system default CA bundle...</p>";

// Test 1: Guzzle (like Google OAuth)
echo "<h2>Test 1: Guzzle Client (Google OAuth simulation)</h2>";
try {
    require_once __DIR__ . '/vendor/autoload.php';
    
    $guzzleClient = new \GuzzleHttp\Client([
        'verify' => true // Using system CA bundle
    ]);
    
    $response = $guzzleClient->get('https://www.google.com');
    echo "✅ <strong>SUCCESS!</strong> Guzzle can connect to HTTPS sites with verify=true<br>";
    echo "Status Code: " . $response->getStatusCode() . "<br>";
} catch (Exception $e) {
    echo "❌ <strong>FAILED!</strong> Error: " . $e->getMessage() . "<br>";
}

// Test 2: cURL (like SendGrid)
echo "<h2>Test 2: cURL (SendGrid simulation)</h2>";
try {
    $ch = curl_init('https://api.sendgrid.com/v3/mail/send');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        echo "❌ <strong>FAILED!</strong> cURL Error: " . $error . "<br>";
    } else {
        echo "✅ <strong>SUCCESS!</strong> cURL can connect to HTTPS sites with SSL verification<br>";
        echo "HTTP Code: " . $httpCode . " (401 is expected - no API key sent)<br>";
    }
} catch (Exception $e) {
    echo "❌ <strong>FAILED!</strong> Error: " . $e->getMessage() . "<br>";
}

// Test 3: Check PHP SSL configuration
echo "<h2>Test 3: PHP SSL Configuration</h2>";
echo "OpenSSL Version: " . OPENSSL_VERSION_TEXT . "<br>";
echo "cURL Version: " . curl_version()['version'] . "<br>";
echo "cURL SSL Version: " . curl_version()['ssl_version'] . "<br>";

// Show CA path
$curlInfo = curl_version();
if (isset($curlInfo['cainfo'])) {
    echo "System CA Bundle: " . $curlInfo['cainfo'] . "<br>";
} else {
    echo "System CA Bundle: Using OS default<br>";
}

echo "<hr>";
echo "<h3>Summary:</h3>";
echo "<p>If both tests show ✅ SUCCESS, your code is ready for deployment!</p>";
echo "<p>If you see ❌ FAILED, there might be an SSL configuration issue on your system.</p>";
?>
