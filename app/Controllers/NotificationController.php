<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use Google\Client;
use Google_Service_FirebaseCloudMessaging;
use Google_Service_FirebaseCloudMessaging_Message;
use Google_Service_FirebaseCloudMessaging_Notification;
use App\Models\TokenModel;
use App\Models\NotificationModel;
use App\Models\DocumentModel;
use App\Controllers\BaseController;

class NotificationController extends BaseController
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
                    'title' => 'Trial',
                    'body' => 'Hello.',
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


    public function store_token()
    {
        // Get the JSON input from the request
        $input = $this->request->getJSON(true);
        $token = $input['token'] ?? null;

        // Check if the token is provided
        if (empty($token)) {
            return $this->response->setJSON([
                'status' => 'failure',
                'message' => 'No token provided'
            ]);
        }

        // Load the TokenModel
        $tokenModel = new TokenModel();

        try {
            // Check if the token already exists in the database
            $existingToken = $tokenModel->where('token', $token)->first();

            if ($existingToken) {
                return $this->response->setJSON([
                    'status' => 'failure',
                    'message' => 'Token already exists in the database'
                ]);
            }

            // Prepare data for insertion
            $data = [
                'token' => $token
            ];

            // Insert the token into the database
            if ($tokenModel->insert($data)) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Token stored successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => 'failure',
                    'message' => 'Failed to store token'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'failure',
                'message' => 'An error occurred while storing the token',
                'error' => $e->getMessage()
            ]);
        }
    }
    public function storeDocumentAndAssignToken()
    {
        $tokenModel = new TokenModel();
        $documentModel = new DocumentModel();
        $notificationModel = new NotificationModel();

        // Get input from the request (assume it's JSON or route parameter)
        $input = $this->request->getJSON(true);
        $trackingNumber = $input['tracking_number'] ?? null;
        $token = $input['token'] ?? null;

        if (empty($trackingNumber) || empty($token)) {
            return $this->response->setJSON([
                'status' => 'failure',
                'message' => 'Missing tracking number or token'
            ]);
        }

        // Retrieve the document based on the tracking number
        $document = $documentModel->where('tracking_number', $trackingNumber)->first();
        if (!$document) {
            return $this->response->setJSON([
                'status' => 'failure',
                'message' => 'Document not found'
            ]);
        }

        // Check if the token exists in the token table
        $existingToken = $tokenModel->where('token', $token)->first();
        if (!$existingToken) {
            return $this->response->setJSON([
                'status' => 'failure',
                'message' => 'Token not found'
            ]);
        }

        // Check if the association already exists (i.e., both document_id and user_id exist in the same row)
        $existingAssociation = $notificationModel->where([
            'document_id' => $document['document_id'],
            'user_id' => $existingToken['id']
        ])->first();

        if ($existingAssociation) {
            return $this->response->setJSON([
                'status' => 'failure',
                'message' => 'Association already exists, no duplicate allowed'
            ]);
        }

        // Prepare data to store in NotificationModel
        $data = [
            'document_id' => $document['document_id'],
            'user_id' => $existingToken['id']
        ];

        // Insert the notification record
        if ($notificationModel->insert($data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Document and token association stored successfully'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'failure',
                'message' => 'Failed to store the association'
            ]);
        }
    }



}