<?php

namespace App\Services;

use Google\Auth\Credentials\ServiceAccountCredentials;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FcmDirectService
{
    public function send(
        string $fcmToken,
        array $data,
        string $priority = 'high',
        string $title = null,
        string $body = null,
        string $image = null
    ): array {
        try {
            $serviceAccountPath = storage_path('app/firebase/service-account.json');

            if (!file_exists($serviceAccountPath)) {
                return ['success' => false, 'error' => 'JSON file missing at: ' . $serviceAccountPath];
            }

            $credentialsArray = json_decode(file_get_contents($serviceAccountPath), true);
            if (!is_array($credentialsArray)) {
                return ['success' => false, 'error' => 'Invalid Firebase service-account JSON'];
            }

            $projectId = trim((string) ($credentialsArray['project_id'] ?? env('FIREBASE_PROJECT_ID', '')));
            if ($projectId === '') {
                return ['success' => false, 'error' => 'Firebase project_id is missing'];
            }

            $accessToken = Cache::remember("fcm_access_token_{$projectId}", 3000, function () use ($credentialsArray) {
                $credentials = new ServiceAccountCredentials(
                    'https://www.googleapis.com/auth/firebase.messaging',
                    $credentialsArray
                );
                $tokenData = $credentials->fetchAuthToken();

                if (isset($tokenData['error'])) {
                    throw new \Exception('Token error: ' . json_encode($tokenData));
                }

                return $tokenData['access_token'];
            });

            Log::info('FCM Token Generated: ' . substr((string) $accessToken, 0, 20) . '...');

            $payload = [
                'message' => [
                    'token' => $fcmToken,
                    'data' => $data,
                ],
            ];

            if ($title || $body || $image) {
                $payload['message']['notification'] = array_filter([
                    'title' => $title,
                    'body' => $body,
                    'image' => $image,
                ]);
            }

            $androidPriority = ($priority === 'high') ? 'high' : 'normal';
            $apnsPriority = ($priority === 'high') ? '10' : '5';

            $payload['message']['android'] = ['priority' => $androidPriority];
            $payload['message']['apns'] = ['headers' => ['apns-priority' => $apnsPriority]];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ])->post("https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send", $payload);

            Log::info('FCM Send Response: ' . $response->body());

            $responseBody = $response->body();
            $decoded = json_decode($responseBody, true);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'body' => $decoded,
                    'message_id' => $decoded['name'] ?? null,
                ];
            }

            $error = $decoded['error'] ?? null;
            $firebaseDetails = [
                'status' => $response->status(),
                'message' => $error['message'] ?? 'FCM Send Failed',
                'code' => $error['code'] ?? null,
                'details' => $error['details'] ?? [],
                'raw_body' => $responseBody,
            ];

            Log::error('FCM Error', $firebaseDetails);

            return [
                'success' => false,
                'error' => $firebaseDetails['message'],
                'firebase_details' => $firebaseDetails,
            ];
        } catch (\Exception $e) {
            Log::error('FCM Exception: ' . $e->getMessage());

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}
