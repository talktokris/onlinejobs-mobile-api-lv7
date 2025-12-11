<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ExpoPushNotificationService
{
    protected $apiUrl = 'https://exp.host/--/api/v2/push/send';

    /**
     * Send push notification to single or multiple tokens
     *
     * @param string|array $tokens Expo push tokens
     * @param string $title Notification title
     * @param string $body Notification body
     * @param array $data Additional data
     * @return array
     */
    public function sendNotification($tokens, $title, $body, $data = [])
    {
        // Ensure tokens is an array
        if (!is_array($tokens)) {
            $tokens = [$tokens];
        }

        // Filter out empty tokens
        $tokens = array_filter($tokens);

        if (empty($tokens)) {
            return [
                'success' => false,
                'message' => 'No valid push tokens provided',
                'data' => null
            ];
        }

        // Prepare messages for batch sending
        $messages = [];
        foreach ($tokens as $token) {
            $messages[] = [
                'to' => $token,
                'sound' => 'default',
                'title' => $title,
                'body' => $body,
                'data' => $data,
            ];
        }

        try {
            $response = Http::timeout(30)->post($this->apiUrl, $messages);

            if ($response->successful()) {
                $responseData = $response->json();
                
                // Check for errors in response
                $errors = [];
                if (isset($responseData['data'])) {
                    foreach ($responseData['data'] as $result) {
                        if (isset($result['status']) && $result['status'] === 'error') {
                            $errors[] = $result['message'] ?? 'Unknown error';
                        }
                    }
                }

                if (!empty($errors)) {
                    Log::warning('Expo Push Notification errors', ['errors' => $errors]);
                    return [
                        'success' => false,
                        'message' => 'Some notifications failed to send',
                        'data' => $responseData,
                        'errors' => $errors
                    ];
                }

                Log::info('Expo Push Notification sent successfully', [
                    'tokens_count' => count($tokens),
                    'response' => $responseData
                ]);

                return [
                    'success' => true,
                    'message' => 'Notifications sent successfully',
                    'data' => $responseData
                ];
            } else {
                Log::error('Expo Push Notification API error', [
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);

                return [
                    'success' => false,
                    'message' => 'Failed to send notifications',
                    'data' => $response->json()
                ];
            }
        } catch (\Exception $e) {
            Log::error('Expo Push Notification exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Error sending notifications: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }

    /**
     * Send notification to a single token (convenience method)
     */
    public function sendToToken($token, $title, $body, $data = [])
    {
        return $this->sendNotification($token, $title, $body, $data);
    }

    /**
     * Send notification to multiple tokens (convenience method)
     */
    public function sendToTokens(array $tokens, $title, $body, $data = [])
    {
        return $this->sendNotification($tokens, $title, $body, $data);
    }
}

