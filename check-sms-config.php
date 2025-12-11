<?php

/**
 * Check SMS Gateway Configuration
 */

// Load environment variables
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

echo "=== SMS Gateway Configuration Check ===\n\n";

$smsGatewayEnabled = $_ENV['SMS_GATEWAY_TWO_FACTOR'] ?? 'not set';
$apiKey = $_ENV['SMS_GATEWAY_API_KEY'] ?? 'not set';
$apiSecret = $_ENV['SMS_GATEWAY_API_KEY_SECRET'] ?? 'not set';
$apiUrl = $_ENV['SMS_GATEWAY_POST_URL'] ?? 'not set';

echo "SMS_GATEWAY_TWO_FACTOR: " . ($smsGatewayEnabled === 'true' || $smsGatewayEnabled === true ? '✅ ENABLED' : '❌ DISABLED or NOT SET') . "\n";
echo "SMS_GATEWAY_POST_URL: $apiUrl\n";
echo "SMS_GATEWAY_API_KEY: " . ($apiKey !== 'not set' ? substr($apiKey, 0, 10) . '...' : '❌ NOT SET') . "\n";
echo "SMS_GATEWAY_API_KEY_SECRET: " . ($apiSecret !== 'not set' && $apiSecret !== 'XXXxxxxxxxxxxxxx' ? substr($apiSecret, 0, 5) . '...' : '❌ NOT SET or DEFAULT') . "\n";

echo "\n";

if ($smsGatewayEnabled === 'true' || $smsGatewayEnabled === true) {
    echo "✅ SMS Gateway is ENABLED - SMS will be sent when requesting OTP\n";
} else {
    echo "❌ SMS Gateway is DISABLED - Default OTP (123456) will be used\n";
    echo "   To enable, set SMS_GATEWAY_TWO_FACTOR=true in .env file\n";
}

echo "\n=== Configuration Check Complete ===\n";

