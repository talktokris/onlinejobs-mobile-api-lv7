<?php

namespace App\Services;

use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class ExpoNotificationService
{
    private $client;
    private $apiUrl = 'https://exp.host/--/api/v2/push/send';

    public function __construct()
    {
        $this->client = new Client([
            'timeout' => 10.0,
        ]);
    }

    /**
     * Send push notification to a single user
     *
     * @param string $expoPushToken
     * @param string $title
     * @param string $body
     * @param array $data
     * @return bool
     */
    public function sendNotification($expoPushToken, $title, $body, $data = [])
    {
        if (empty($expoPushToken)) {
            Log::warning('Expo push token is empty');
            return false;
        }

        try {
            $payload = [
                'to' => $expoPushToken,
                'sound' => 'default',
                'title' => $title,
                'body' => $body,
                'data' => $data,
            ];

            $response = $this->client->post($this->apiUrl, [
                'json' => [$payload],
                'headers' => [
                    'Accept' => 'application/json',
                    'Accept-Encoding' => 'gzip, deflate',
                    'Content-Type' => 'application/json',
                ],
            ]);

            $result = json_decode($response->getBody()->getContents(), true);
            
            if (isset($result['data'][0]['status']) && $result['data'][0]['status'] === 'ok') {
                Log::info('Push notification sent successfully', ['token' => substr($expoPushToken, 0, 20) . '...']);
                return true;
            } else {
                Log::error('Failed to send push notification', ['result' => $result]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Error sending push notification', [
                'error' => $e->getMessage(),
                'token' => substr($expoPushToken, 0, 20) . '...'
            ]);
            return false;
        }
    }

    /**
     * Send push notification to multiple users
     *
     * @param array $expoPushTokens
     * @param string $title
     * @param string $body
     * @param array $data
     * @return array
     */
    public function sendBulkNotifications($expoPushTokens, $title, $body, $data = [])
    {
        if (empty($expoPushTokens)) {
            return ['success' => 0, 'failed' => 0];
        }

        $payloads = [];
        foreach ($expoPushTokens as $token) {
            if (!empty($token)) {
                $payloads[] = [
                    'to' => $token,
                    'sound' => 'default',
                    'title' => $title,
                    'body' => $body,
                    'data' => $data,
                ];
            }
        }

        if (empty($payloads)) {
            return ['success' => 0, 'failed' => 0];
        }

        try {
            // Expo API allows up to 100 notifications per request
            $chunks = array_chunk($payloads, 100);
            $successCount = 0;
            $failedCount = 0;

            foreach ($chunks as $chunk) {
                $response = $this->client->post($this->apiUrl, [
                    'json' => $chunk,
                    'headers' => [
                        'Accept' => 'application/json',
                        'Accept-Encoding' => 'gzip, deflate',
                        'Content-Type' => 'application/json',
                    ],
                ]);

                $result = json_decode($response->getBody()->getContents(), true);
                
                if (isset($result['data'])) {
                    foreach ($result['data'] as $item) {
                        if (isset($item['status']) && $item['status'] === 'ok') {
                            $successCount++;
                        } else {
                            $failedCount++;
                        }
                    }
                }
            }

            Log::info('Bulk push notifications sent', [
                'success' => $successCount,
                'failed' => $failedCount
            ]);

            return ['success' => $successCount, 'failed' => $failedCount];
        } catch (\Exception $e) {
            Log::error('Error sending bulk push notifications', [
                'error' => $e->getMessage()
            ]);
            return ['success' => 0, 'failed' => count($expoPushTokens)];
        }
    }

    /**
     * Send notification to user by user ID
     *
     * @param int $userId
     * @param string $title
     * @param string $body
     * @param array $data
     * @return bool
     */
    public function sendToUser($userId, $title, $body, $data = [])
    {
        $user = User::find($userId);
        
        if (!$user || empty($user->expo_push_token) || $user->notification_enabled == 0) {
            return false;
        }

        return $this->sendNotification($user->expo_push_token, $title, $body, $data);
    }

    /**
     * Send notification to all job seekers
     *
     * @param string $title
     * @param string $body
     * @param array $data
     * @return array
     */
    public function sendToAllJobSeekers($title, $body, $data = [])
    {
        // Get all job seekers (role_id = 1) with enabled notifications and valid tokens
        $jobSeekers = User::whereIn('id', function ($query) {
            $query->select('user_id')
                ->from('role_user')
                ->where('role_id', 1);
        })
        ->where('notification_enabled', 1)
        ->whereNotNull('expo_push_token')
        ->where('expo_push_token', '!=', '')
        ->pluck('expo_push_token')
        ->toArray();

        if (empty($jobSeekers)) {
            Log::info('No job seekers with valid push tokens found');
            return ['success' => 0, 'failed' => 0];
        }

        return $this->sendBulkNotifications($jobSeekers, $title, $body, $data);
    }
}

