<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\OfficeModel;
use App\Models\DocumentModel;
use App\Models\DocumentClassificationModel;
use App\Models\DocumentHistoryModel;
use App\Models\TimeProcessingModel;
use ResponseTrait;
use SimpleSoftwareIO\QrCode\Generator;

class OfficeController extends BaseController
{

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $userId = session('user_id');
    
        $userModel = new \App\Models\UserModel();
        $user = $userModel->find($userId);
        $userName = $user['first_name'] . ' ' . $user['last_name'];

    
        $officeId = session('office_id');

        $officeModel = new OfficeModel();
        $office = $officeModel->find($officeId);
        // Check if office data is retrieved and office_name exists
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
            'user' => $user,
        ];
    
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
        'user' => $user
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
        'user' => $user,
    ];

    return view('Office/Completed', $data);
}


public function updateDocumentStatus($documentId, $newStatus)
{
    $documentModel = new DocumentModel();
    $workflowModel = new DocumentHistoryModel();
    $timeProcessingModel = new TimeProcessingModel();

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
    }
    
    return redirect()->back();
}


public function updateDocumentCompletedStatus($documentId, $newStatus)
{
    $documentModel = new DocumentModel();
    $workflowModel = new DocumentHistoryModel();
    $timeProcessingModel = new TimeProcessingModel();

    $documentModel->update($documentId, ['status' => $newStatus]);

    $userId = session()->get('user_id');
    $officeId = session()->get('office_id');

    $historyData = [
        'document_id' => $documentId,
        'user_id' => $userId,
        'office_id' => $officeId,
        'status' => $newStatus,
        'date_changed' => date('Y-m-d H:i:s'),
        'date_completed' => date('Y-m-d H:i:s')
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
            'completed_timestamp' => date('Y-m-d H:i:s')
        ];
        $timeProcessingModel->insert($timeProcessingData);
    }

    return redirect()->back();
}

    public function updateDocumentDeletedStatus($documentId, $newStatus)
    {
        $documentModel = new DocumentModel(); 
        $workflowModel = new DocumentHistoryModel();

        $documentModel->update($documentId, ['status' => $newStatus]);

        $userId = session()->get('user_id');
        $officeId = session()->get('office_id');

        $data = [
            'document_id' => $documentId,
            'user_id' => $userId,
            'office_id' => $officeId,
            'status' => $newStatus,
            'date_changed' => date('Y-m-d H:i:s'),
            'date_deleted' => $newStatus === 'deleted' ? date('Y-m-d H:i:s') : null
        ];
        $workflowModel->insert($data);

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
                documents.document_id,
                documents.tracking_number, 
                documents.title, 
                documents.sender_id, 
                documents.sender_office_id,
                documents.recipient_id,
                documents.status, 
                document_history.user_id,
                document_history.office_id as current_office_id,
                document_history.status as history_status,
                document_history.date_changed,
                document_history.date_completed,
                offices.office_name as recipient_office_name
            FROM documents
            JOIN document_history ON documents.document_id = document_history.document_id
            LEFT JOIN offices ON documents.recipient_id = offices.office_id
            WHERE documents.recipient_id = $office_id
            AND document_history.status = 'completed'
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
                $sender_office = '';
            } else {
                $officeModel = new OfficeModel();
                $office = $officeModel->find($sender_office_id);
                $sender_name = '';
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
    
        return view('Office/History', $data);
    }
    

    public function manageprofile()
    {
        $userId = session('user_id');

        $session = session();
        $office_id = $session->get('office_id');

        $officeModel = new OfficeModel();
        $office = $officeModel->find($office_id);
        $office_name = $office['office_name'];
        
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
    
        $profileImage = $request->getFile('profileImage');
        if ($profileImage && $profileImage->isValid()) {
            $newName = $profileImage->getRandomName();
            if ($profileImage->move(ROOTPATH . 'public/uploads', $newName)) {
                $user['picture_path'] = $newName;
            } else {
                return 'Error: Unable to upload profile image.';
            }
        }
    
        $user['first_name'] = $request->getVar('firstName');
        $user['last_name'] = $request->getVar('lastName');
        $user['email'] = $request->getVar('email');
        if ($request->getVar('password') != null || $request->getVar('password') != ''){
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
    
        $data = [
            'user_name' => $userName,
            'searchResults' => [],
            'offices' => $offices,
            'office_name' => $office_name,
            'user' => $user
        ];
    
        return view('Office/Search', $data);
    }
    

    public function search()
    {
        $userId = session('user_id');

        $session = session();
        $office_id = $session->get('office_id');

        $officeModel = new OfficeModel();
        $office = $officeModel->find($office_id);
        $office_name = $office['office_name'];

        $userModel = new \App\Models\UserModel();
        $user = $userModel->find($userId);
        $userName = $user['first_name'] . ' ' . $user['last_name'];
    
        $searchQuery = $this->request->getVar('search');
        $statusFilter = $this->request->getVar('status');
        $sortOption = $this->request->getVar('sort');
    
        $session = session();
        $officeId = $session->get('office_id');
    
        $query = $this->db->table('documents')
            ->groupStart()
            ->like('title', $searchQuery)
            ->orLike('tracking_number', $searchQuery)
            ->groupEnd()
            ->where('recipient_id', $officeId);
    
        if (!empty($statusFilter)) {
            $query->where('status', $statusFilter);
        }
    
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
    
        $searchResults = $query->get()->getResultArray();
    
        $data = [
            'searchResults' => $searchResults,
            'office_name' => $office_name,
            'user' => $user
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
    
        // Initialize QR Code Generator
        $qrcode = new \SimpleSoftwareIO\QrCode\Generator;
    
        // Generate QR code
        $qrCodeURL = $qrcode->size(200)->generate($url);
    
        // Return the QR code HTML
        return $this->response->setJSON(['qrCode' => $qrCodeURL]);
    }
    
    

}



