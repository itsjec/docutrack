<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use Google\Client;
use Google_Service_FirebaseCloudMessaging;
use Google_Service_FirebaseCloudMessaging_Message;
use Google_Service_FirebaseCloudMessaging_Notification;

class NotificationController extends Controller
{
    // View to display notification interface
    public function index()
    {
        return view('notification_view');
    }

    public function send_notification()
{
    // Get the token from the POST request payload
    $input = $this->request->getJSON(true);
    $token = $input['token'] ?? null;

    if (empty($token)) {
        return $this->response->setJSON([
            'status' => 'failure',
            'message' => 'No token provided'
        ]);
    }

    $keyFilePath = APPPATH . 'Services/Firebase/service-account-file.json';

    // Initialize Google Client
    $client = new Client();
    $client->setAuthConfig($keyFilePath);
    $client->addScope('https://www.googleapis.com/auth/firebase.messaging');

    // Construct the notification payload
    $payload = [
        'message' => [
            'token' => $token,
            'notification' => [
                'title' => 'Test Notification',
                'body' => 'This is a test push notification.',
            ],
            'android' => [
                'priority' => 'high',
            ],
        ],
    ];

    try {
        // Send the message using the FCM API
        $httpClient = $client->authorize();
        $response = $httpClient->post(
            'https://fcm.googleapis.com/v1/projects/push-notif-7fe44/messages:send',
            [
                'json' => $payload,
            ]
        );

        $responseBody = json_decode($response->getBody(), true);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Notification sent successfully.',
            'response' => $responseBody
        ]);
    } catch (\Exception $e) {
        return $this->response->setJSON([
            'status' => 'failure',
            'message' => 'Failed to send notification.',
            'error' => $e->getMessage()
        ]);
    }
}

    


}