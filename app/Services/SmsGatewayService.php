<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsGatewayService
{
    protected $apiUrl;
    protected $apiKey;
    protected $apiSecret;

    public function __construct()
    {
        $this->apiUrl = env('SMS_GATEWAY_POST_URL', 'https://sms.360.my/gw/bulk360/v3_0/send.php');
        $this->apiKey = env('SMS_GATEWAY_API_KEY');
        $this->apiSecret = env('SMS_GATEWAY_API_KEY_SECRET');
    }

    /**
     * Send OTP SMS to mobile number
     * 
     * @param string $mobileNumber Mobile number in international format (without + sign, e.g., 609205537552)
     * @param string $otp 6-digit OTP code
     * @param string|null $from Sender ID (optional, max 11 alphanumeric characters)
     * @return array ['success' => bool, 'message' => string, 'data' => array|null]
     */
    public function sendOtp($mobileNumber, $otp, $from = null)
    {
        try {
            // Validate API credentials
            if (empty($this->apiKey) || empty($this->apiSecret)) {
                Log::error('SMS Gateway: API credentials not configured');
                return [
                    'success' => false,
                    'message' => 'SMS gateway not configured',
                    'data' => null
                ];
            }

            // Format OTP message
            $message = "Your OTP code is: {$otp}. Valid for 3 minutes.";

            // Prepare request parameters (URL encode for GET request)
            $user = urlencode($this->apiKey);
            $pass = urlencode($this->apiSecret);
            $to = $mobileNumber;
            $text = rawurlencode($message);
            
            // Build URL with query parameters (matching sample code format)
            $url = $this->apiUrl . "?user=$user&pass=$pass&to=$to&text=$text";
            
            // Add sender ID if provided (not applicable in Malaysia but can be used)
            if ($from) {
                $fromParam = substr($from, 0, 11); // Max 11 characters
                $url .= "&from=" . urlencode($fromParam);
            }

            // Send SMS via HTTP GET (matching sample code)
            $response = Http::timeout(30)->get($url);

            // Check response
            if ($response->successful()) {
                $responseData = $response->json();
                
                // Check if response indicates success
                if (isset($responseData['code']) && $responseData['code'] == 200) {
                    Log::info('SMS sent successfully', [
                        'mobile' => $mobileNumber,
                        'ref' => $responseData['ref'] ?? null
                    ]);
                    
                    return [
                        'success' => true,
                        'message' => 'SMS sent successfully',
                        'data' => $responseData
                    ];
                } else {
                    Log::error('SMS Gateway error response', [
                        'mobile' => $mobileNumber,
                        'response' => $responseData
                    ]);
                    
                    return [
                        'success' => false,
                        'message' => $responseData['desc'] ?? 'Failed to send SMS',
                        'data' => $responseData
                    ];
                }
            } else {
                Log::error('SMS Gateway HTTP error', [
                    'mobile' => $mobileNumber,
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                
                return [
                    'success' => false,
                    'message' => 'Failed to connect to SMS gateway',
                    'data' => ['status' => $response->status(), 'body' => $response->body()]
                ];
            }
        } catch (\Exception $e) {
            Log::error('SMS Gateway exception', [
                'mobile' => $mobileNumber,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'message' => 'Error sending SMS: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }

    /**
     * Format mobile number with country code
     * 
     * @param string $countryCode Country code (e.g., "60" for Malaysia)
     * @param string $mobileNumber Mobile number without country code
     * @return string Formatted mobile number (e.g., "609205537552")
     */
    public function formatMobileNumber($countryCode, $mobileNumber)
    {
        // Remove any + signs or spaces
        $countryCode = str_replace(['+', ' ', '-'], '', $countryCode);
        $mobileNumber = str_replace(['+', ' ', '-'], '', $mobileNumber);
        
        // Remove leading 0 from mobile number if country code is provided
        if (substr($mobileNumber, 0, 1) === '0' && !empty($countryCode)) {
            $mobileNumber = substr($mobileNumber, 1);
        }
        
        return $countryCode . $mobileNumber;
    }
}

