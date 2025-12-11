<?php

/**
 * Test SMS Gateway Script
 * This script tests the 360.my SMS gateway by sending a test message
 * 
 * Usage: php test-sms-gateway.php
 */

// Load environment variables (if running from Laravel context)
if (file_exists(__DIR__ . '/.env')) {
    $envFile = file_get_contents(__DIR__ . '/.env');
    $lines = explode("\n", $envFile);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            if (!empty($key)) {
                $_ENV[$key] = $value;
            }
        }
    }
}

// Get credentials from environment or use defaults
$apiKey = $_ENV['SMS_GATEWAY_API_KEY'] ?? 'z3YaOZGexq';
$apiSecret = $_ENV['SMS_GATEWAY_API_KEY_SECRET'] ?? 'XXXxxxxxxxxxxxxx';
$apiUrl = $_ENV['SMS_GATEWAY_POST_URL'] ?? 'https://sms.360.my/gw/bulk360/v3_0/send.php';

// Test mobile number (with country code 60 for Malaysia)
$testMobile = '60162104126'; // 60 (Malaysia) + 162104126
$testMessage = 'Test OTP: 123456. This is a test message from Online Jobs app.';

echo "=== SMS Gateway Test ===\n\n";
echo "API URL: $apiUrl\n";
echo "API Key: $apiKey\n";
echo "API Secret: " . substr($apiSecret, 0, 5) . "..." . "\n";
echo "Mobile Number: $testMobile\n";
echo "Message: $testMessage\n\n";

// Prepare parameters
$params = [
    'user' => $apiKey,
    'pass' => $apiSecret,
    'to' => $testMobile,
    'text' => $testMessage,
];

// Build URL with parameters
$url = $apiUrl . '?' . http_build_query($params);

echo "Request URL: " . str_replace($apiSecret, '***', $url) . "\n\n";

// Send SMS using cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

echo "=== Response ===\n";
echo "HTTP Code: $httpCode\n";

if ($curlError) {
    echo "cURL Error: $curlError\n";
}

if ($response) {
    echo "Response Body: $response\n";
    
    // Try to decode JSON response
    $responseData = json_decode($response, true);
    if ($responseData) {
        echo "\n=== Parsed Response ===\n";
        echo "Code: " . ($responseData['code'] ?? 'N/A') . "\n";
        echo "Description: " . ($responseData['desc'] ?? 'N/A') . "\n";
        echo "Reference ID: " . ($responseData['ref'] ?? 'N/A') . "\n";
        echo "Balance: " . ($responseData['balance'] ?? 'N/A') . "\n";
        echo "Currency: " . ($responseData['currency'] ?? 'N/A') . "\n";
        
        if (isset($responseData['code']) && $responseData['code'] == 200) {
            echo "\n✅ SUCCESS: SMS sent successfully!\n";
            echo "Reference ID: " . ($responseData['ref'] ?? 'N/A') . "\n";
        } else {
            echo "\n❌ ERROR: SMS sending failed!\n";
            echo "Error: " . ($responseData['desc'] ?? 'Unknown error') . "\n";
        }
    } else {
        echo "\n⚠️  Warning: Response is not valid JSON\n";
    }
} else {
    echo "❌ ERROR: No response received from SMS gateway\n";
}

echo "\n=== Test Complete ===\n";

