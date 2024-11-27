<?php

namespace App\Controllers;
use Google\Client;
use App\Models\TokenModel;
use App\Models\NotificationModel;
use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\OfficeModel;
use App\Models\DocumentModel;
use App\Models\ClassificationModel;
use App\Models\DocumentHistoryModel;
use App\Models\TimeProcessingModel;
use ResponseTrait;
use SimpleSoftwareIO\QrCode\Generator;

class OfficeController extends BaseController
{
    protected function getUserData()
    {
        $userModel = new UserModel();
        return $userModel->find(session()->get('user_id'));
    }

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->db = \Config\Database::connect();
        $this->user = $this->getUserData();
    }

    public function index()
    {
        $userId = session('user_id');

        $userModel = new \App\Models\UserModel();
        $user = $userModel->find($userId);
        $userName = $user['first_name'] . ' ' . $user['last_name'];
        $user['picture_path'] = $user['picture_path'] ?? 'path/to/default/image.jpg';


        $officeId = session('office_id');

        $officeModel = new OfficeModel();
        $office = $officeModel->find($officeId);
        if ($office) {
            $office_name = isset($office['office_name']) ? $office['office_name'] : 'Unknown Office';
        } else {
            $office_name = 'No Office Found';
        }

        $documentModel = new \App\Models\DocumentModel();
        $documents = $documentModel->findAll();

        $db = \Config\Database::connect();

        $pending_documents_count = $db->table('documents')
            ->where('recipient_id', $officeId)
            ->where('status', 'pending')
            ->countAllResults();

        $received_documents_count = $db->table('documents')
            ->where('recipient_id', $officeId)
            ->where('status', 'on process')
            ->countAllResults();

        $total_documents_count = $db->table('documents')
            ->where('recipient_id', $officeId)
            ->countAllResults();

        $documents = $db->table('documents')
            ->select('documents.*, IFNULL(offices.office_name, CONCAT(users.first_name, " ", users.last_name)) as sender')
            ->join('offices', 'offices.office_id = documents.sender_office_id', 'left')
            ->join('users', 'users.user_id = documents.sender_id', 'left')
            ->where('recipient_id', $officeId)
            ->orderBy('documents.date_of_document', 'DESC')
            ->get()
            ->getResult();

        return view('Office/Index', [
            'documents' => $documents,
            'pending_documents_count' => $pending_documents_count,
            'received_documents_count' => $received_documents_count,
            'total_documents_count' => $total_documents_count,
            'office_name' => $office_name,
            'user' => $user,
        ]);
    }


    public function pending()
    {
        $userId = session('user_id');

        $userModel = new \App\Models\UserModel();
        $user = $userModel->find($userId);
        $userName = $user['first_name'] . ' ' . $user['last_name'];


        $session = session();
        $office_id = $session->get('office_id');

        $officeModel = new OfficeModel();
        $office = $officeModel->find($office_id);
        $office_name = $office['office_name'];

        $db = db_connect();

        $query = $db->query("
            SELECT 
                documents.title, 
                documents.tracking_number, 
                documents.sender_id, 
                documents.sender_office_id, 
                documents.status, 
                documents.action, 
                documents.description, 
                documents.document_id
            FROM documents
            WHERE documents.recipient_id = $office_id
            AND documents.status = 'pending'
            ORDER BY documents.date_of_document DESC
        ");

        $documents = $query->getResult();

        // Log the documents to check if action and description are present
        foreach ($documents as $document) {
            // Log the document details
            log_message('info', 'Document ID: ' . $document->document_id . ', Action: ' . $document->action . ', Description: ' . $document->description);
        }

        $senderDetails = [];
        foreach ($documents as $document) {
            $sender_id = $document->sender_id;
            $sender_office_id = $document->sender_office_id;

            if ($sender_office_id === null) {
                $userModel = new UserModel();
                $user = $userModel->find($sender_id);
                $sender_name = $user['first_name'] . ' ' . $user['last_name'];
                $sender_office = 'N/A';
            } else {
                $officeModel = new OfficeModel();
                $office = $officeModel->find($sender_office_id);
                $sender_name = 'N/A';
                $sender_office = $office['office_name'];
            }

            $senderDetails[$document->document_id] = [
                'sender_user' => $sender_name,
                'sender_office' => $sender_office
            ];
        }

        $data = [
            'documents' => $documents,
            'senderDetails' => $senderDetails,
            'office_name' => $office_name,
        ];

        $data['user'] = $this->user;

        return view('Office/Pending', $data);
    }


    public function ongoing()
    {

        $userId = session('user_id');

        $userModel = new \App\Models\UserModel();
        $user = $userModel->find($userId);
        $userName = $user['first_name'] . ' ' . $user['last_name'];

        $session = session();
        $office_id = $session->get('office_id');

        $officeModel = new OfficeModel();
        $office = $officeModel->find($office_id);
        $office_name = $office['office_name'];

        $db = db_connect();

        $query = $db->query("
        SELECT 
            documents.title, 
            documents.tracking_number, 
            documents.sender_id, 
            documents.sender_office_id, 
            documents.status, 
            documents.action, 
            documents.document_id
        FROM documents
        WHERE documents.recipient_id = $office_id
        AND documents.status = 'on process'
        ORDER BY documents.date_of_document DESC
    ");

        $documents = $query->getResult();

        $senderDetails = [];
        foreach ($documents as $document) {
            $sender_id = $document->sender_id;
            $sender_office_id = $document->sender_office_id;

            if ($sender_office_id === null) {
                $userModel = new UserModel();
                $user = $userModel->find($sender_id);
                $sender_name = $user['first_name'] . ' ' . $user['last_name'];
                $sender_office = 'N/A';
            } else {
                $officeModel = new OfficeModel();
                $office = $officeModel->find($sender_office_id);
                $sender_name = 'N/A';
                $sender_office = $office['office_name'];
            }

            $senderDetails[$document->document_id] = [
                'sender_user' => $sender_name,
                'sender_office' => $sender_office
            ];
        }

        $data = [
            'documents' => $documents,
            'senderDetails' => $senderDetails,
            'office_name' => $office_name,
            'user' => $user,
        ];

        return view('Office/OnGoing', $data);
    }


    public function received()
    {

        $userId = session('user_id');

        $userModel = new \App\Models\UserModel();
        $user = $userModel->find($userId);
        $userName = $user['first_name'] . ' ' . $user['last_name'];

        $session = session();
        $office_id = $session->get('office_id');

        $officeModel = new OfficeModel();
        $office = $officeModel->find($office_id);
        $office_name = $office['office_name'];

        $db = db_connect();

        $query = $db->query("
        SELECT 
            documents.title, 
            documents.tracking_number, 
            documents.sender_id, 
            documents.sender_office_id, 
            documents.status, 
            documents.action, 
            documents.document_id
        FROM documents
        WHERE documents.recipient_id = $office_id
        AND documents.status = 'received'
        ORDER BY documents.date_of_document DESC
    ");

        $documents = $query->getResult();

        $senderDetails = [];
        foreach ($documents as $document) {
            if ($document->sender_office_id === null) {
                $sender_user_id = $document->sender_id;
                $senderOffice = 'N/A';
            } else {
                $sender_user_id = null;
                $senderOfficeModel = new OfficeModel();
                $senderOffice = $senderOfficeModel->find($document->sender_office_id);
                $senderOffice = $senderOffice['office_name'];
            }

            if ($sender_user_id !== null) {
                $senderUserModel = new UserModel();
                $senderUser = $senderUserModel->find($sender_user_id);
                $senderDetails[$document->document_id] = [
                    'sender_user' => $senderUser['first_name'] . ' ' . $senderUser['last_name'],
                    'sender_office' => $senderOffice
                ];
            } else {
                $senderDetails[$document->document_id] = [
                    'sender_user' => 'N/A',
                    'sender_office' => $senderOffice
                ];
            }
        }

        $data = [
            'documents' => $documents,
            'senderDetails' => $senderDetails,
            'office_name' => $office_name,
            'user' => $user,
        ];

        return view('Office/Received', $data);
    }



    public function completed()
    {

        $userId = session('user_id');

        $userModel = new \App\Models\UserModel();
        $user = $userModel->find($userId);
        $userName = $user['first_name'] . ' ' . $user['last_name'];

        $session = session();
        $office_id = $session->get('office_id');

        $officeModel = new OfficeModel();
        $office = $officeModel->find($office_id);
        $office_name = $office['office_name'];

        if (!$office_id) {
            return 'Error: Office ID not set';
        }

        $db = db_connect();

        // Fetch the offices
        $officesModel = new OfficeModel();
        $offices = $officesModel->findAll();

        $query = $db->query("
        SELECT 
            documents.title, 
            documents.tracking_number, 
            documents.sender_id, 
            documents.sender_office_id, 
            documents.status, 
            documents.action, 
            documents.document_id
        FROM documents
        WHERE documents.recipient_id = $office_id
        AND documents.status = 'completed'
        ORDER BY documents.date_of_document DESC
    ");

        if (!$query) {
            // Handle error if query fails
            return 'Error: Unable to fetch completed documents';
        }

        $documents = $query->getResult();

        $senderDetails = [];
        foreach ($documents as $document) {
            $sender_user_id = $document->sender_id;
            $sender_office_id = $document->sender_office_id;

            if ($sender_office_id === null) {
                $userModel = new UserModel();
                $user = $userModel->find($sender_user_id);
                $sender_name = $user['first_name'] . ' ' . $user['last_name'];
                $sender_office = 'N/A';
            } else {
                $officeModel = new OfficeModel();
                $office = $officeModel->find($sender_office_id);
                $sender_name = 'N/A';
                $sender_office = $office['office_name'];
            }

            $senderDetails[$document->document_id] = [
                'sender_user' => $sender_name,
                'sender_office' => $sender_office
            ];
        }

        $data = [
            'offices' => $offices,
            'documents' => $documents,
            'senderDetails' => $senderDetails,
            'office_name' => $office_name,
        ];
        $data['user'] = $this->user;

        return view('Office/Completed', $data);
    }


    public function updateDocumentStatus($documentId, $newStatus)
    {
        $documentModel = new DocumentModel();
        $workflowModel = new DocumentHistoryModel();
        $timeProcessingModel = new TimeProcessingModel();
        $notificationModel = new NotificationModel();
        $tokenModel = new TokenModel();

        log_message('info', "Updating document (ID: $documentId) status to: $newStatus");

        $document = $documentModel->find($documentId);

        $documentModel->update($documentId, ['status' => $newStatus]);

        $userId = session()->get('user_id');
        $officeId = session()->get('office_id');

        $historyData = [
            'document_id' => $documentId,
            'user_id' => $userId,
            'office_id' => $officeId,
            'status' => $newStatus,
            'date_changed' => date('Y-m-d H:i:s'),
            'date_completed' => null
        ];
        $workflowModel->insert($historyData);

        if ($newStatus === 'received') {
            $timeProcessingData = [
                'document_id' => $documentId,
                'office_id' => $officeId,
                'received_timestamp' => date('Y-m-d H:i:s'),
                'completed_timestamp' => null
            ];
            $timeProcessingModel->insert($timeProcessingData);

            $notification = $notificationModel->where('document_id', $documentId)->first();
            if ($notification) {
                $associatedUserId = $notification['user_id'];

                $tokenEntry = $tokenModel->where('id', $associatedUserId)->first();
                if ($tokenEntry) {
                    $token = $tokenEntry['token'];

                    $documentTitle = $document['title'];
                    $message = "Your Document titled '{$documentTitle}' has been marked as Received";

                    $this->send_notification($token, $message, "Document Received.");
                }
            }
        }
            if ($newStatus === 'on process') {
                $timeProcessingData = [
                    'document_id' => $documentId,
                    'office_id' => $officeId,
                    'received_timestamp' => null,
                    'completed_timestamp' => null
                ];
                $timeProcessingModel->insert($timeProcessingData);
            
                $notification = $notificationModel->where('document_id', $documentId)->first();
                if ($notification) {
                    $associatedUserId = $notification['user_id'];
            
                    $tokenEntry = $tokenModel->where('id', $associatedUserId)->first();
                    if ($tokenEntry) {
                        $token = $tokenEntry['token'];
            
                        $documentTitle = $document['title'];
                        $message = "Your document titled '{$documentTitle}' is currently on process.";
            
                        $this->send_notification($token, $message, "Document On Process.");
                    }
                }
            }
    }

    private function send_notification($token, $title, $body)
    {
        // Add your Firebase notification sending logic here
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
                    'title' => $title,
                    'body' => $body,
                ],
                'android' => [
                    'priority' => 'high',
                ],
            ],
        ];

        try {
            // Log the payload to check if the values are correct
            log_message('info', 'Sending notification with payload: ' . json_encode($payload));

            // Send the message using the FCM API
            $httpClient = $client->authorize();
            $response = $httpClient->post(
                'https://fcm.googleapis.com/v1/projects/push-notif-7fe44/messages:send',
                [
                    'json' => $payload,
                ]
            );

            // Check the response status code
            $statusCode = $response->getStatusCode();
            log_message('info', 'FCM Status Code: ' . $statusCode);

            // Get the response body
            $responseBody = json_decode($response->getBody(), true);

            // Log the full response for debugging
            log_message('info', 'FCM Response Body: ' . json_encode($responseBody));

            if ($statusCode == 200) {
                return [
                    'status' => 'success',
                    'message' => 'Notification sent successfully.',
                    'response' => $responseBody
                ];
            } else {
                log_message('error', 'FCM error: ' . json_encode($responseBody));
                return [
                    'status' => 'failure',
                    'message' => 'Failed to send notification.',
                    'error' => $responseBody
                ];
            }
        } catch (\Exception $e) {
            log_message('error', 'Failed to send notification: ' . $e->getMessage());
            return [
                'status' => 'failure',
                'message' => 'Failed to send notification.',
                'error' => $e->getMessage()
            ];
        }
    }


    public function updateDocumentCompletedStatus($documentId, $newStatus)
    {
        $documentModel = new DocumentModel();
        $workflowModel = new DocumentHistoryModel();
        $timeProcessingModel = new TimeProcessingModel();
        $notificationModel = new NotificationModel();
        $tokenModel = new TokenModel();
    
        $document = $documentModel->find($documentId);
        if (!$document) {
            log_message('error', "Document not found: {$documentId}");
            throw new \Exception("Document not found.");
        }
    
        $documentModel->update($documentId, ['status' => $newStatus]);
    
        $userId = session()->get('user_id');
        $officeId = session()->get('office_id');
    
        $historyData = [
            'document_id' => $documentId,
            'user_id' => $userId,
            'office_id' => $officeId,
            'status' => $newStatus,
            'date_changed' => date('Y-m-d H:i:s'),
            'date_completed' => ($newStatus === 'completed') ? date('Y-m-d H:i:s') : null,
        ];
        $workflowModel->insert($historyData);
    
        $existingTimeProcessing = $timeProcessingModel
            ->where('document_id', $documentId)
            ->where('office_id', $officeId)
            ->first();
    
        if ($existingTimeProcessing) {
            $timeProcessingModel->update($existingTimeProcessing['id'], ['completed_timestamp' => date('Y-m-d H:i:s')]);
        } else {
            $timeProcessingData = [
                'document_id' => $documentId,
                'office_id' => $officeId,
                'received_timestamp' => null,
                'completed_timestamp' => ($newStatus === 'completed') ? date('Y-m-d H:i:s') : null,
            ];
            $timeProcessingModel->insert($timeProcessingData);
        }
    
        if ($newStatus === 'completed') {
            $notification = $notificationModel->where('document_id', $documentId)->first();
            if ($notification) {
                $associatedUserId = $notification['user_id'];
                $tokenEntry = $tokenModel->where('id', $associatedUserId)->first();
    
                if ($tokenEntry) {
                    $token = $tokenEntry['token'];
                    $documentTitle = $document['title'];
                    $message = "Your document titled '{$documentTitle}' has been marked as completed.";
    
                    $this->send_notification($token, $message, 'Document Completed');
                } else {
                    log_message('error', "Token entry not found for user ID: {$associatedUserId}");
                }
            } else {
                log_message('error', "Notification not found for document ID: {$documentId}");
            }
        }
    
        return redirect()->back();
    }
    

    public function updateDocumentDeletedStatus($documentId, $newStatus)
    {
        $documentModel = new DocumentModel();
        $workflowModel = new DocumentHistoryModel();
        $notificationModel = new NotificationModel();
        $tokenModel = new TokenModel();
    
        // Update document status
        $documentModel->update($documentId, ['status' => $newStatus]);
    
        // Check if document exists
        $document = $documentModel->find($documentId);
        if (!$document) {
            log_message('error', "Document not found: {$documentId}");
            throw new \Exception("Document not found.");
        }
    
        $userId = session()->get('user_id');
        $officeId = session()->get('office_id');
    
        // Log the status change in workflow history
        $data = [
            'document_id' => $documentId,
            'user_id' => $userId,
            'office_id' => $officeId,
            'status' => $newStatus,
            'date_changed' => date('Y-m-d H:i:s'),
            'date_deleted' => $newStatus === 'deleted' ? date('Y-m-d H:i:s') : null,
        ];
        $workflowModel->insert($data);
    
        if ($newStatus === 'deleted') {
            $notification = $notificationModel->where('document_id', $documentId)->first();
            if ($notification) {
                $associatedUserId = $notification['user_id'];
    
                $tokenEntry = $tokenModel->where('id', $associatedUserId)->first();
                if ($tokenEntry) {
                    $token = $tokenEntry['token'];
                    $documentTitle = $document['title'];
    
                    $message = "Your document titled '{$documentTitle}' has been deleted.";
    
                    $this->send_notification($token, $message, 'Document Deleted');
                } else {
                    log_message('error', "Token entry not found for user ID: {$associatedUserId}");
                }
            } else {
                log_message('error', "Notification not found for document ID: {$documentId}");
            }
        }
    
        // Redirect back to the previous page
        return redirect()->back();
    }
    
    public function updateDocumentRecipientAndStatus($documentId, $newRecipientId, $newStatus)
    {
        $documentModel = new DocumentModel();
        $historyModel = new DocumentHistoryModel();

        $db = db_connect();
        $db->transBegin();

        try {
            $document = $documentModel->find($documentId);
            if (!$document) {
                throw new \Exception('Document not found');
            }

            $action = $this->request->getPost('action');
            $description = $this->request->getPost('description');

            $documentModel->update($documentId, [
                'recipient_id' => $newRecipientId,
                'status' => $newStatus,
                'action' => $action,
                'description' => $description
            ]);

            $historyData = [
                'document_id' => $documentId,
                'user_id' => session()->get('user_id'),
                'office_id' => $newRecipientId,
                'status' => $newStatus,
                'action' => $action,
                'description' => $description,
                'date_changed' => date('Y-m-d H:i:s'),
                'date_completed' => null
            ];
            $historyModel->insert($historyData);

            $db->transCommit();

            return 'Document updated successfully';
        } catch (\Exception $e) {
            $db->transRollback();
            return 'Error: ' . $e->getMessage();
        }
    }

    public function sendOutDocument()
    {
        $documentId = $this->request->getPost('document_id');
        $recipientId = $this->request->getPost('recipient_id');
        $action = $this->request->getPost('action');
        $description = $this->request->getPost('description');

        $this->updateDocumentRecipientAndStatus($documentId, $recipientId, 'pending', $action, $description);

        return 'Document sent out successfully';
    }


    private function getCurrentOfficeId($documentId)
    {
        $db = \Config\Database::connect();
        $query = $db->table('offices_documents')
            ->select('office_id')
            ->where('document_id', $documentId)
            ->get();

        if ($query->getNumRows() > 0) {
            return $query->getRow()->office_id;
        }

        return null;
    }


    public function getDocumentInfo()
    {
        $documentId = $this->request->getPost('document_id');

        $model = new DocumentModel();
        $document = $model->find($documentId);

        if (!$document) {
            return $this->fail('Document not found.');
        }

        return $this->respond([
            'title' => $document['title'],
            'tracking_number' => $document['tracking_number']
        ]);
    }


    public function history()
    {
        $userId = session('user_id');
        $session = session();
        $office_id = $session->get('office_id');

        if (!$office_id) {
            return 'Error: Office ID not set';
        }

        $officeModel = new OfficeModel();
        $office = $officeModel->find($office_id);
        $office_name = $office['office_name'];

        $db = db_connect();

        $query = $db->query("
            SELECT 
                documents.document_id,
                documents.tracking_number, 
                documents.title, 
                documents.sender_id, 
                documents.sender_office_id,
                documents.recipient_id,
                document_history.user_id AS completed_by_user_id,
                document_history.office_id AS current_office_id,
                document_history.status AS history_status,
                document_history.date_completed,
                offices.office_name AS recipient_office_name
            FROM documents
            JOIN document_history ON documents.document_id = document_history.document_id
            LEFT JOIN offices ON documents.recipient_id = offices.office_id
            WHERE document_history.office_id = $office_id
            AND document_history.status = 'completed'
        ");

        if (!$query) {
            return 'Error: Unable to fetch completed documents';
        }

        $documents = $query->getResult();
        $senderDetails = [];
        $completedByDetails = [];

        // Instantiate UserModel outside the loop
        $userModel = new UserModel();

        foreach ($documents as $document) {
            $sender_user_id = $document->sender_id;
            $sender_office_id = $document->sender_office_id;

            if ($sender_office_id === null) {
                $user = $userModel->find($sender_user_id);
                $sender_name = $user ? ($user['first_name'] . ' ' . $user['last_name']) : 'Unknown';
                $sender_office = '';
            } else {
                $office = $officeModel->find($sender_office_id);
                $sender_name = '';
                $sender_office = $office['office_name'];
            }

            $senderDetails[$document->document_id] = [
                'sender_user' => $sender_name,
                'sender_office' => $sender_office
            ];

            // Fetch completed by user details
            $completedByUser = $userModel->find($document->completed_by_user_id);
            if ($completedByUser) {
                $completedByName = $completedByUser['first_name'] . ' ' . $completedByUser['last_name'];
            } else {
                $completedByName = 'Unknown';
            }

            $completedByDetails[$document->document_id] = $completedByName;
        }

        $data = [
            'documents' => $documents,
            'senderDetails' => $senderDetails,
            'completedByDetails' => $completedByDetails, // Pass completed_by details
            'office_name' => $office_name,
        ];

        // Return the view with the data
        return view('Office/History', $data);
    }




    public function manageprofile()
    {
        $userId = session('user_id');

        $session = session();
        $office_id = $session->get('office_id');

        $officeModel = new OfficeModel();
        $office = $officeModel->find($office_id);
        $office_name = $office ? $office['office_name'] : 'Unknown Office';

        if (!$userId) {
            return 'Error: User ID not set';
        }

        $userModel = new \App\Models\UserModel();
        $user = $userModel->find($userId);

        if (!$user) {
            return 'Error: User not found';
        }

        $userName = $user['first_name'] . ' ' . $user['last_name'];

        $data = [
            'user' => $user,
            'office_name' => $office_name,
        ];

        return view('Office/ManageProfile', $data);
    }


    public function updateProfile()
    {
        $request = service('request');
        $userModel = new \App\Models\UserModel();

        $userId = session('user_id');
        $user = $userModel->find($userId);

        if (!$user) {
            return 'Error: User not found';
        }

        // Handle profile picture upload
        $profileImage = $request->getFile('profileImage');
        if ($profileImage && $profileImage->isValid()) {
            $filename = $profileImage->getRandomName();
            if ($profileImage->move('public/uploads', $filename)) {
                $user['picture_path'] = 'public/uploads/' . $filename;
            } else {
                return 'Error: Unable to upload profile image.';
            }
        }

        // Update other user information
        $user['first_name'] = $request->getVar('firstName');
        $user['last_name'] = $request->getVar('lastName');
        $user['email'] = $request->getVar('email');

        if ($request->getVar('password')) {
            $user['password'] = password_hash($request->getVar('password'), PASSWORD_DEFAULT);
        }

        $userModel->update($userId, $user);

        return redirect()->to('/manageprofile');
    }





    public function trash()
    {

        $userId = session('user_id');

        $userModel = new \App\Models\UserModel();
        $user = $userModel->find($userId);
        $userName = $user['first_name'] . ' ' . $user['last_name'];

        $session = session();
        $office_id = $session->get('office_id');

        $officeModel = new OfficeModel();
        $office = $officeModel->find($office_id);
        $office_name = $office['office_name'];

        if (!$office_id) {
            return 'Error: Office ID not set';
        }

        $db = db_connect();

        $query = $db->query("
            SELECT 
                documents.document_id AS id,
                documents.tracking_number, 
                documents.title, 
                CONCAT(users.first_name, ' ', users.last_name) AS deleted_by,
                document_history.date_deleted
            FROM document_history
            JOIN documents ON documents.document_id = document_history.document_id
            JOIN users ON users.user_id = document_history.user_id
            WHERE document_history.office_id = $office_id
            AND document_history.status = 'deleted'
        ");

        $documents = $query->getResult();

        $data = [
            'documents' => $documents,
            'office_name' => $office_name,
            'user' => $user,
        ];


        return view('Office/Trash', $data);
    }



    public function testInsertDocumentHistory()
    {
        $documentHistoryModel = new DocumentHistoryModel();

        $historyData = [
            'document_id' => 3,
            'user_id' => 7,
            'office_id' => 2,
            'status' => 'received',
            'date_changed' => date('Y-m-d H:i:s')
        ];

        $documentHistoryModel->insert($historyData);

        return 'Document history inserted successfully for testing';
    }

    public function deleteDocument($documentId)
    {
        $documentModel = new DocumentModel();
        $result = $documentModel->delete($documentId);

        if ($result) {
            $response = ['success' => true];
        } else {
            $response = ['success' => false];
        }

        return $this->response->setJSON($response);
    }

    public function allDocuments()
    {
        $userId = session('user_id');
        $session = session();
        $office_id = $session->get('office_id');

        $userModel = new \App\Models\UserModel();
        $user = $userModel->find($userId);
        $userName = $user['first_name'] . ' ' . $user['last_name'];

        $officeModel = new OfficeModel();
        $offices = $officeModel->findAll();
        $office = $officeModel->find($office_id);
        $office_name = $office['office_name'];

        $documentModel = new \App\Models\DocumentModel();
        $documents = $documentModel->where('sender_office_id', $office_id)->findAll();

        $data = [
            'user_name' => $userName,
            'searchResults' => $documents,
            'offices' => $offices,
            'office_name' => $office_name,
            'user' => $user
        ];

        return view('Office/Search', $data);
    }



    public function search()
    {
        $session = session();
        $userId = $session->get('user_id');
        $officeId = $session->get('office_id');

        $userModel = new \App\Models\UserModel();
        $user = $userModel->find($userId);
        $userName = $user['first_name'] . ' ' . $user['last_name'];

        $officeModel = new OfficeModel();
        $office = $officeModel->find($officeId);
        $officeName = $office['office_name'];

        $documentModel = new DocumentModel();
        $query = $documentModel
            ->where('sender_office_id', $officeId);

        $status = $this->request->getVar('status');
        if (!empty($status)) {
            $query->where('status', $status);
        }

        $search = $this->request->getVar('search');
        if (!empty($search)) {
            $query->groupStart()
                ->like('title', $search)
                ->orLike('tracking_number', $search)
                ->groupEnd();
        }

        $sortOption = $this->request->getVar('sort');
        switch ($sortOption) {
            case 'title_asc':
                $query->orderBy('title', 'ASC');
                break;
            case 'title_desc':
                $query->orderBy('title', 'DESC');
                break;
            case 'date_asc':
                $query->orderBy('date_of_document', 'ASC');
                break;
            case 'date_desc':
                $query->orderBy('date_of_document', 'DESC');
                break;
            default:
                $query->orderBy('title', 'ASC');
        }

        $searchResults = $query->findAll();

        $data = [
            'user_name' => $userName,
            'searchResults' => $searchResults,
            'office_name' => $officeName,
            'user' => $user,
        ];

        return view('Office/Search', $data);
    }


    public function getDocumentDetails($id)
    {
        $documentModel = new DocumentModel();
        $officeModel = new OfficeModel();
        $userModel = new UserModel();

        $document = $documentModel->find($id);

        if ($document) {
            $senderName = '';

            if ($document->sender_id === null) {
                $office = $officeModel->find($document->sender_office_id);
                if ($office) {
                    $senderName = $office->office_name;
                }
            } else {
                $user = $userModel->find($document->sender_id);
                if ($user) {
                    $senderName = $user->first_name . ' ' . $user->last_name;
                }
            }

            $document->senderName = $senderName;

            $data = [
                'document' => $document
            ];

            return $this->response->setJSON($data);
        } else {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Document not found']);
        }
    }

    public function generate()
    {
        $url = $this->request->getPost('url');

        if (!$url) {
            return $this->response->setJSON(['error' => 'No URL provided']);
        }

        $qrcode = new \SimpleSoftwareIO\QrCode\Generator;

        // Generate QR code
        $qrCodeURL = $qrcode->size(200)->generate($url);

        // Return the QR code HTML
        return $this->response->setJSON(['qrCode' => $qrCodeURL]);
    }

    public function adddocumentdepartment()
    {
        $session = session();
        $officeId = session('office_id');

        $officeModel = new OfficeModel();
        $office = $officeModel->find($officeId);
        if ($office) {
            $office_name = isset($office['office_name']) ? $office['office_name'] : 'Unknown Office';
        } else {
            $office_name = 'No Office Found';
        }

        $documentModel = new DocumentModel();
        $documents = $documentModel
            ->select('documents.document_id, documents.version_number, documents.title, documents.tracking_number, documents.sender_office_id, documents.recipient_id, documents.status, documents.date_of_document, documents.action, documents.description, documents.attachment, sender.office_name AS sender_office_name, recipient.office_name AS recipient_office_name, c.classification_name AS classification, c.sub_classification AS sub_classification')
            ->join('(SELECT document_id, MAX(version_number) AS max_version FROM documents GROUP BY document_id) latest', 'documents.document_id = latest.document_id AND documents.version_number = latest.max_version', 'inner')
            ->join('classification c', 'c.classification_id = documents.classification_id', 'left')
            ->join('offices sender', 'sender.office_id = documents.sender_office_id', 'left')
            ->join('offices recipient', 'recipient.office_id = documents.recipient_id', 'left')
            ->where('documents.status !=', 'deleted')
            ->where('documents.sender_office_id', $officeId)
            ->whereIn('(documents.title, documents.version_number)', function ($builder) {
                return $builder->select('title, MAX(version_number)')
                    ->from('documents')
                    ->groupBy('title');
            })
            ->orderBy('documents.date_of_document', 'DESC')
            ->findAll();

        $classificationModel = new ClassificationModel();
        $classifications = $classificationModel
            ->distinct()
            ->select('classification_name')
            ->where('status', 'active')
            ->findColumn('classification_name');

        $classificationsDropdown = is_array($classifications) ? array_values($classifications) : [];

        $subClassifications = $classificationModel
            ->where('status', 'active')
            ->findAll();

        $subClassificationsDropdown = array_column($subClassifications, 'sub_classification');



        $officeModel = new OfficeModel();
        $offices = $officeModel->where('status', 'active')->findAll();
        $officesDropdown = [];
        foreach ($offices as $office) {
            $officesDropdown[$office['office_id']] = $office['office_name'];
        }

        $data = [
            'documents' => $documents,
            'classificationsDropdown' => $classificationsDropdown,
            'subClassificationsDropdown' => $subClassificationsDropdown,
            'officesDropdown' => $officesDropdown,
            'office_name' => $office_name,
        ];

        $data['user'] = $this->user;

        return view('Office/AddDepartment', $data);
    }


    public function adddocumentclient()
    {
        $officeId = session('office_id');

        $officeModel = new OfficeModel();
        $office = $officeModel->find($officeId);
        if ($office) {
            $office_name = isset($office['office_name']) ? $office['office_name'] : 'Unknown Office';
        } else {
            $office_name = 'No Office Found';
        }

        $userModel = new UserModel();
        $guestUsers = $userModel->where('role', 'guest')->findAll();

        $documentModel = new DocumentModel();
        $documents = $documentModel
            ->select('documents.document_id, documents.version_number, documents.title, documents.tracking_number, documents.sender_id, documents.recipient_id, documents.attachment, documents.status, documents.date_of_document, documents.action, documents.description, documents.attachment, sender.first_name AS sender_first_name, sender.last_name AS sender_last_name, recipient.office_name AS recipient_office_name, c.classification_name AS classification, c.sub_classification AS sub_classification')
            ->join('(SELECT document_id, MAX(version_number) AS max_version FROM documents GROUP BY document_id) latest', 'documents.document_id = latest.document_id AND documents.version_number = latest.max_version', 'inner')
            ->join('classification c', 'c.classification_id = documents.classification_id', 'left')
            ->join('users sender', 'sender.user_id = documents.sender_id', 'left')
            ->join('offices recipient', 'recipient.office_id = documents.recipient_id', 'left')
            ->where('documents.status !=', 'deleted')
            ->where('sender.role', 'guest')
            ->where('documents.recipient_id', $officeId)
            ->whereIn('documents.title', function ($builder) {
                $builder->select('title')
                    ->from('documents')
                    ->groupBy('title');
            })
            ->orderBy('documents.date_of_document', 'DESC')
            ->findAll();


        $classificationModel = new ClassificationModel();
        $classifications = $classificationModel
            ->distinct()
            ->select('classification_name')
            ->where('status', 'active')
            ->findColumn('classification_name');

        $classificationsDropdown = is_array($classifications) ? array_values($classifications) : [];

        $subClassifications = $classificationModel
            ->where('status', 'active')
            ->findAll();

        $subClassificationsDropdown = array_column($subClassifications, 'sub_classification');


        $officeModel = new OfficeModel();
        $offices = $officeModel->where('status', 'active')->findAll();
        $officesDropdown = [];
        foreach ($offices as $office) {
            $officesDropdown[$office['office_id']] = $office['office_name'];
        }

        $guestUsersNames = [];
        foreach ($guestUsers as $user) {
            if ($user['status'] === 'activate') {
                $guestUsersNames[$user['user_id']] = $user['first_name'] . ' ' . $user['last_name'];
            }
        }
        $data = [
            'documents' => $documents,
            'classificationsDropdown' => $classificationsDropdown,
            'subClassificationsDropdown' => $subClassificationsDropdown,
            'officesDropdown' => $officesDropdown,
            'guestUsersNames' => $guestUsersNames,
            'office_name' => $office_name,

        ];

        $data['user'] = $this->user;

        return view('Office/AddClient', $data);
    }

    public function getSubClassifications()
    {
        $classification = $this->request->getPost('classification');

        $classificationModel = new ClassificationModel();
        $subClassifications = $classificationModel
            ->where('classification_name', $classification)
            ->where('sub_classification !=', null)
            ->where('sub_classification !=', '')
            ->findAll();

        return $this->response->setJSON($subClassifications);
    }

    public function getClassifications()
    {
        return $this->distinct()->findColumn('classification_name');
    }

    public function saveClientDocument()
    {
        helper(['form', 'url']);

        $validationRules = [
            'title' => 'required',
            'description' => 'required',
            'classification' => 'required',
            'sub_classification' => 'required',
            'action' => 'required',
            'sender_office_id' => 'required',
            'recipient_office_id' => 'required'
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $file = $this->request->getFile('attachment');
        if (!$file->isValid() || $file->getClientMimeType() !== 'application/pdf') {
            return redirect()->back()->withInput()->with('errors', ['attachment' => 'Invalid file type. Only PDF files are allowed.']);
        }

        $newName = $file->getRandomName();
        $file->move(ROOTPATH . 'public/uploads/', $newName);

        $fullPath = 'public/uploads/' . $newName;

        $db = \Config\Database::connect();
        $builder = $db->table('documents');

        $classification = $this->request->getVar('classification');
        $subClassification = $this->request->getVar('sub_classification');
        $title = $this->request->getVar('title');

        try {
            $db->transBegin();
            $latestDocument = $builder->where('title', $title)->orderBy('version_number', 'DESC')->get()->getRowArray();

            if ($latestDocument) {
                $parent_id = $latestDocument['document_id'];
                $versionParts = explode('.', $latestDocument['version_number']);
                if (count($versionParts) == 2) {
                    $major = intval($versionParts[0]);
                    $major++;
                    $version_number = $major . '.0';
                } else {
                    $version_number = '2.0';
                }
            } else {
                $parent_id = NULL;
                $version_number = '1.0';
            }

            $trackingNumber = 'TR-' . uniqid();
            $data = [
                'tracking_number' => $trackingNumber,
                'sender_id' => $this->request->getVar('sender_office_id'),
                'sender_office_id' => NULL,
                'recipient_id' => $this->request->getVar('recipient_office_id'),
                'status' => 'pending',
                'title' => $title,
                'description' => $this->request->getVar('description'),
                'action' => $this->request->getVar('action'),
                'date_of_document' => date('Y-m-d'),
                'attachment' => $fullPath,
                'classification_id' => NULL,
                'classification' => $classification,
                'sub_classification' => $subClassification,
                'date_completed' => NULL,
                'version_number' => $version_number,
                'parent_id' => $parent_id
            ];
            $builder->insert($data);

            $db->transCommit();

            return redirect()->to(base_url('clienttracking?trackingNumber=' . $trackingNumber));
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('errors', $e->getMessage());
        }
    }


    public function saveDepartmentDocument()
    {
        helper(['form', 'url']);

        $validationRules = [
            'title' => 'required',
            'sender_office_id' => 'required',
            'recipient_office_id' => 'required',
            'classification' => 'required',
            'sub_classification' => 'required',
            'date_of_document' => 'required',
            'attachment' => 'uploaded[attachment]|mime_in[attachment,application/pdf]',
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $attachment = $this->request->getFile('attachment');
        $attachmentName = $attachment->getRandomName();
        $attachment->move(ROOTPATH . 'public/uploads', $attachmentName);

        $trackingNumber = 'TR-' . uniqid();

        $db = \Config\Database::connect();
        $builder = $db->table('documents');

        $title = $this->request->getPost('title');
        $senderOfficeId = $this->request->getPost('sender_office_id');
        $recipientOfficeId = $this->request->getPost('recipient_office_id');

        $existingDocument = $builder
            ->where('title', $title)
            ->where('sender_office_id', $senderOfficeId)
            ->where('recipient_id', $recipientOfficeId)
            ->orderBy('version_number', 'DESC')
            ->get()
            ->getRowArray();

        if ($existingDocument) {
            $parent_id = $existingDocument['document_id'];
            $versionParts = explode('.', $existingDocument['version_number']);
            if (count($versionParts) == 2) {
                $major = intval($versionParts[0]);
                $major++;
                $version_number = $major . '.0';
            } else {
                $version_number = '2.0';
            }
        } else {
            $parent_id = NULL;
            $version_number = '1.0';
        }

        $data = [
            'tracking_number' => $trackingNumber,
            'sender_id' => NULL,
            'title' => $title,
            'sender_office_id' => $senderOfficeId,
            'recipient_id' => $recipientOfficeId,
            'status' => 'pending',
            'classification' => $this->request->getPost('classification'),
            'sub_classification' => $this->request->getPost('sub_classification'),
            'date_of_document' => date('Y-m-d H:i:s', strtotime($this->request->getPost('date_of_document'))),
            'attachment' => $attachmentName,
            'action' => $this->request->getPost('action'),
            'description' => $this->request->getPost('description'),
            'classification_id' => NULL,
            'date_completed' => NULL,
            'version_number' => $version_number,
            'parent_id' => $parent_id
        ];

        $builder->insert($data);

        return redirect()->to(base_url('departmenttracking?trackingNumber=' . $trackingNumber));
    }

    public function departmenttracking()
    {

        $officeId = session('office_id');

        $officeModel = new OfficeModel();
        $office = $officeModel->find($officeId);
        if ($office) {
            $office_name = isset($office['office_name']) ? $office['office_name'] : 'Unknown Office';
        } else {
            $office_name = 'No Office Found';
        }

        $data = ['office_name' => $office_name,];

        return view('Office/DepartmentTracking', $data);
    }

    public function clienttracking()
    {

        $officeId = session('office_id');

        $officeModel = new OfficeModel();
        $office = $officeModel->find($officeId);
        if ($office) {
            $office_name = isset($office['office_name']) ? $office['office_name'] : 'Unknown Office';
        } else {
            $office_name = 'No Office Found';
        }

        $data = ['office_name' => $office_name,];

        return view('Office/ClientTracking', $data);
    }

    public function updateDocument()
    {
        $documentId = $this->request->getPost('id');
        $documentModel = new DocumentModel();

        $documentData = [
            'title' => $this->request->getPost('title'),
            'sender_office_id' => $this->request->getPost('sender_office_id'),
            'recipient_id' => $this->request->getPost('recipient_office_id'),
            'action' => $this->request->getPost('action'),
            'description' => $this->request->getPost('description'),
        ];

        if ($this->request->getFile('attachment')->isValid()) {
            $attachment = $this->request->getFile('attachment');
            $attachment->move(WRITEPATH . 'uploads');
            $documentData['attachment'] = $attachment->getName();
        }

        if (!empty($documentData)) {
            $result = $documentModel->set($documentData)->where('document_id', $documentId)->update();
            if ($result) {
                echo '<script>window.location.reload();</script>';
                exit();
            }
        }

        return redirect()->to('adddepartmentdocument')->with('success', 'Document updated successfully.');
    }

    public function updateGuestDocument()
    {
        $documentId = $this->request->getPost('document_id');
        $documentModel = new DocumentModel();

        $documentData = [
            'title' => $this->request->getPost('title'),
            'sender_id' => $this->request->getPost('sender_id'),
            'recipient_id' => $this->request->getPost('recipient_office_id'),
            'action' => $this->request->getPost('action'),
            'description' => $this->request->getPost('description'),
        ];

        if ($this->request->getFile('attachment')->isValid()) {
            $attachment = $this->request->getFile('attachment');
            $attachment->move(WRITEPATH . 'uploads');
            $documentData['attachment'] = $attachment->getName();
        }

        if (!empty($documentData)) {
            $result = $documentModel->set($documentData)->where('document_id', $documentId)->update();
            if ($result) {
                echo '<script>window.location.reload();</script>';
                exit();
            }
        }

        return redirect()->to('addclientdocument')->with('success', 'Document updated successfully.');
    }

    public function archiveDocument()
    {
        $documentModel = new DocumentModel();

        if ($this->request->isAJAX()) {
            $documentId = $this->request->getPost('documentId');

            $result = $documentModel->delete($documentId);

            if ($result) {
                return $this->response->setJSON(['success' => true]);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete document.']);
            }
        }
    }

    public function archiveClientDocument()
    {
        $documentModel = new DocumentModel();

        if ($this->request->isAJAX()) {
            $documentId = $this->request->getPost('documentId');

            $result = $documentModel->delete($documentId);

            if ($result) {
                return $this->response->setJSON(['success' => true]);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete document.']);
            }
        }
    }


    public function officemaintenance()
    {
        $officeId = session('office_id');

        $officeModel = new OfficeModel();
        $office = $officeModel->find($officeId);
        if ($office) {
            $office_name = isset($office['office_name']) ? $office['office_name'] : 'Unknown Office';
        } else {
            $office_name = 'No Office Found';
        }

        $classificationModel = new ClassificationModel();

        $classifications = $classificationModel->where('status', 'active')->findAll();

        $distinctClassifications = $classificationModel
            ->where('status', 'active')
            ->distinct()
            ->findColumn('classification_name') ?? [];


        $classificationsDropdown = [];
        foreach ($distinctClassifications as $classification) {
            $classificationsDropdown[] = $classification;
        }

        $data['classifications'] = $classifications;
        $data['classificationsDropdown'] = $classificationsDropdown;
        $data['office_name'] = $office_name;
        $data['user'] = $this->user;

        return view('Office/Maintenance', $data);
    }

    public function updateDepartmentClassification()
    {
        $classificationModel = new ClassificationModel();

        $classification_id = $this->request->getPost('officeId');
        $classificationModel->update($classification_id, ['status' => 'deleted']);

        return redirect()->to(base_url('officemaintenance'));
    }
    public function updateClassification()
    {
        $classificationId = $this->request->getPost('classificationId');
        $newClassificationName = $this->request->getPost('classification');
        $newSubClassificationName = $this->request->getPost('subclassificationName');

        $classificationModel = new ClassificationModel();
        $classificationModel->where('classification_id', $classificationId)
            ->set(['classification_name' => $newClassificationName, 'sub_classification' => $newSubClassificationName])
            ->update();

        return redirect()->to('officemaintenance');
    }

    public function saveDocuClassification()
    {
        $classificationModel = new ClassificationModel();

        $classificationName = $this->request->getPost('classificationName');

        $data = [
            'classification_name' => $classificationName,
            'sub_classification' => NULL
        ];

        $classificationModel->insert($data);

        return redirect()->to('officemaintenance')->with('success', 'Classification added successfully.');
    }

    public function saveSubClassification()
    {
        $classificationModel = new ClassificationModel();

        $classificationName = $this->request->getPost('classification');
        $subClassificationName = $this->request->getPost('subclassification');

        $data = [
            'classification_name' => $classificationName,
            'sub_classification' => $subClassificationName
        ];

        $classificationModel->insert($data);

        return redirect()->to('officemaintenance')->with('success', 'Subclassification added successfully.');
    }

    public function manageofficeuser()
    {
        $officeId = session('office_id');

        $userId = session('user_id');

        $userModel = new \App\Models\UserModel();
        $user = $userModel->find($userId);
        $userName = $user['first_name'] . ' ' . $user['last_name'];
        $user['picture_path'] = $user['picture_path'] ?? 'path/to/default/image.jpg'; // Set default if not available


        $officeModel = new OfficeModel();
        $office = $officeModel->find($officeId);

        if ($office) {
            $office_name = isset($office['office_name']) ? $office['office_name'] : 'Unknown Office';
        } else {
            $office_name = 'No Office Found';
        }

        $userModel = new UserModel();
        $users = $userModel->select('users.*, offices.office_name')
            ->join('offices', 'offices.office_id = users.office_id', 'left')
            ->where('users.office_id', $officeId)
            ->whereIn('users.role', ['admin', 'office user'])
            ->findAll();

        $data['offices'] = $officeModel->findAll();
        $data['users'] = $users;
        $data['office_name'] = $office_name;
        $data['user'] = $this->user;


        return view('Office/ManageOfficeUser', $data);
    }


    public function manageclient()
    {

        $officeId = session('office_id');

        $officeModel = new OfficeModel();
        $office = $officeModel->find($officeId);
        if ($office) {
            $office_name = isset($office['office_name']) ? $office['office_name'] : 'Unknown Office';
        } else {
            $office_name = 'No Office Found';
        }

        $userModel = new UserModel();
        $data['guestUsers'] = $userModel->select('user_id, first_name, last_name, email, picture_path')
            ->where('role', 'guest')
            ->where('status', 'activate')
            ->findAll();
        $data['office_name'] = $office_name;

        $data['user'] = $this->user;
        return view('Office/ManageClient', $data);
    }
    public function updateOfficeUser()
    {
        $userId = $this->request->getPost('userId');
        $userModel = new UserModel();

        $userData = [
            'username' => $this->request->getPost('username'),
        ];

        $newPassword = $this->request->getPost('password');
        if (!empty($newPassword)) {
            $userData['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        if ($this->request->getFile('profilePic')->isValid()) {
            $file = $this->request->getFile('profilePic');
            $filename = $file->getRandomName();
            $file->move('public/uploads', $filename);

            $userData['picture_path'] = 'public/uploads/' . $filename;
        }

        $userModel->update($userId, $userData);

        return redirect()->to('manageofficeuser')->with('success', 'User updated successfully.');
    }



    public function saveOfficeUser()
    {
        $userId = session()->get('user_id');
        $officeId = session()->get('office_id');

        log_message('info', 'Session user_id: ' . $userId);
        log_message('info', 'Session office_id: ' . $officeId);

        if (!$officeId) {
            log_message('error', 'Office ID is missing from the session.');
            return $this->response->setJSON([
                'error' => 'Office ID is missing from the session. Please log in again or contact the administrator.'
            ]);
        }

        $officeModel = new OfficeModel();
        $office = $officeModel->find($officeId);

        log_message('info', 'Fetched office data: ' . json_encode($office));

        $officeName = $office ? ($office['office_name'] ?? 'Unknown Office') : 'No Office Found';

        $firstName = $this->request->getPost('firstName');
        $lastName = $this->request->getPost('lastName');
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        log_message('info', 'Form data: ' . json_encode([
            'firstName' => $firstName,
            'lastName' => $lastName,
            'username' => $username,
        ]));

        if (!$this->isValidPassword($password)) {
            log_message('error', 'Password validation failed.');
            return $this->response->setJSON([
                'error' => 'Password must contain at least 8 characters, including uppercase, lowercase, and numbers.'
            ]);
        }

        $userModel = new UserModel();
        $existingUser = $userModel->where('username', $username)
            ->orWhere('email', $username)
            ->first();

        if ($existingUser) {
            log_message('info', 'Existing user found: ' . json_encode($existingUser));
            return $this->response->setJSON([
                'error' => 'Account already exists. Please use a different username.'
            ]);
        }

        $userData = [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'username' => $username,
            'email' => $username,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'office_id' => $officeId,
            'image' => '',
            'role' => 'office user',
        ];

        log_message('info', 'User data to be inserted: ' . json_encode($userData));

        $userModel->insert($userData);

        log_message('info', 'User successfully inserted with office_id: ' . $officeId);

        return $this->response->setJSON([
            'success' => 'Office user added successfully with office_id: ' . $officeId
        ]);
    }

    // Helper function to validate password
    private function isValidPassword($password)
    {
        return preg_match('/[A-Z]/', $password) &&
            preg_match('/[a-z]/', $password) &&
            preg_match('/[0-9]/', $password) &&
            strlen($password) >= 8;
    }



    public function addUserModal()
    {
        $userModel = new UserModel();
        $officeModel = new OfficeModel();

        // Retrieve office_id and user_id from the session
        $officeId = session('office_id');
        $userId = session('user_id');

        // Check if the office_id exists in the session
        if (!$officeId || !$userId) {
            return redirect()->to('login'); // Redirect if either ID is not set
        }

        // Find the office using the office_id
        $office = $officeModel->find($officeId);
        if (!$office) {
            return redirect()->to('login'); // Handle case when office not found
        }

        // Pass the office name and ID to the view
        $data['loggedInUserOfficeName'] = $office['office_name'];
        $data['loggedInUserOfficeId'] = $officeId;

        return view('manageofficeuser', $data); // Pass $data to the view
    }



    public function manageguest()
    {
        $officeId = session('office_id');

        $officeModel = new OfficeModel();
        $office = $officeModel->find($officeId);
        if ($office) {
            $office_name = isset($office['office_name']) ? $office['office_name'] : 'Unknown Office';
        } else {
            $office_name = 'No Office Found';
        }

        $userModel = new UserModel();
        $data['guestUsers'] = $userModel->select('user_id, first_name, last_name, email, picture_path,status')
            ->where('role', 'guest')
            ->findAll();


        $data['offices'] = $officeModel->findAll();
        $data['office_name'] = $office_name;
        $data['user'] = $this->user;

        return view('Office/ManageGuest', $data);
    }

    public function saveofficeguest()
    {
        $userModel = new UserModel();

        $firstName = $this->request->getPost('firstName');
        $lastName = $this->request->getPost('lastName');
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        if (
            !preg_match('/[A-Z]/', $password) ||
            !preg_match('/[a-z]/', $password) ||
            !preg_match('/[0-9]/', $password) ||
            strlen($password) < 8
        ) {
            return $this->response->setJSON(['error' => 'Password must contain at least 8 characters, including uppercase, lowercase, and numbers.']);
        }

        $existingUser = $userModel->where('email', $email)
            ->where('role', 'guest')
            ->first();

        if ($existingUser) {
            return $this->response->setJSON(['error' => 'Account already exists. Please add a new one.']);
        } else {
            $userData = [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'office_id' => null,
                'image' => '',
                'role' => 'guest',
            ];

            $userModel->insert($userData);

            return $this->response->setJSON(['success' => 'Guest user added successfully.']);
        }
    }

    public function updateUser()
    {
        $userId = $this->request->getPost('userId');
        $userModel = new UserModel();

        $userData = [
            'first_name' => $this->request->getPost('firstName'),
            'last_name' => $this->request->getPost('lastName'),
            'email' => $this->request->getPost('email'),
        ];

        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $userData['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $userModel->update($userId, $userData);

        return redirect()->to('manageofficeguest');
    }

    public function activateguestUser()
    {
        $userId = $this->request->getPost('user_id');
        $model = new UserModel();

        $model->update($userId, ['status' => 'activate']);

        return $this->response->setJSON(['status' => 'success']);
    }

    public function deactivateguestUser()
    {
        $userId = $this->request->getPost('user_id');
        $model = new UserModel();

        $model->update($userId, ['status' => 'deactivate']);

        return $this->response->setJSON(['status' => 'success']);
    }

    public function testLogging()
    {
        log_message('info', 'Test log message - info level.');
        log_message('error', 'Test log message - error level.');
        return 'Check your logs';
    }

    public function getOfficeList()
    {
        $officeModel = new OfficeModel();
        $offices = $officeModel->select([
            'office_id',
            'office_name'
        ])->where('status', 'active')->findAll();

        return $this->response->setJSON($offices);
    }

    public function getGuestList()
    {
        $userModel = new UserModel();
        $guests = $userModel->select([
            'user_id',
            'first_name',
            'last_name'
        ])
            ->where('role', 'guest')
            ->findAll();
        return $this->response->setJSON($guests);
    }



}



