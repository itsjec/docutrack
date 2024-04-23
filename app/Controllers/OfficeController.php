<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\OfficeModel;
use App\Models\DocumentModel;
use App\Models\DocumentClassificationModel;
use App\Models\DocumentHistoryModel;
use ResponseTrait;

class OfficeController extends BaseController
{

    public function __construct()
    {
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        $userId = session('user_id');
    
        $userModel = new \App\Models\UserModel();
        $user = $userModel->find($userId);
        $userName = $user['first_name'] . ' ' . $user['last_name'];
    
        $officeId = session('office_id');
    
        $documentModel = new \App\Models\DocumentModel();
        $documents = $documentModel->findAll();
    
        $db = \Config\Database::connect();
    
        // Count pending documents
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
            'user_name' => $userName
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
        AND documents.status = 'pending'
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
        'user_name' => $userName
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
        'user_name' => $userName
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
        'user_name' => $userName
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
        'user_name' => $userName
    ];

    return view('Office/Completed', $data);
}

    public function updateDocumentStatus($documentId, $newStatus)
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
                'date_completed' => NULL
            ];
            $workflowModel->insert($data);
        
            return redirect()->back();
        }

        public function updateDocumentCompletedStatus($documentId, $newStatus)
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
                'date_completed' => date('Y-m-d H:i:s')
            ];
            $workflowModel->insert($data);
        
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
        
            // Begin a database transaction
            $db = db_connect();
            $db->transBegin();
        
            try {
                // Check if the document exists
                $document = $documentModel->find($documentId);
                if (!$document) {
                    throw new \Exception('Document not found');
                }
        
                // Update the document
                $documentModel->update($documentId, ['recipient_id' => $newRecipientId, 'status' => $newStatus]);
        
                // Insert into document history
                $historyData = [
                    'document_id' => $documentId,
                    'user_id' => session()->get('user_id'),
                    'office_id' => session()->get('office_id'),
                    'status' => $newStatus,
                    'date_changed' => date('Y-m-d H:i:s'),
                    'date_completed' => NULL
                ];
                $historyModel->insert($historyData);
        
                // Commit the transaction
                $db->transCommit();
        
                return 'Document updated successfully'; 
            } catch (\Exception $e) {
                // Rollback the transaction on error
                $db->transRollback();
                return 'Error: ' . $e->getMessage();
            }
        }
        

        public function sendOutDocument()
        {
            $documentId = $this->request->getPost('document_id');
            $recipientId = $this->request->getPost('recipient_id');
        
            $this->updateDocumentRecipientAndStatus($documentId, $recipientId, 'pending');
        
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
        'user_name' => $userName
    ];
    
        return view('Office/History', $data);
    }
    

      

    public function manageprofile()
    {
        $userId = session('user_id');
        
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
            'user_name' => $userName
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
            $profileImage->move(ROOTPATH . '/uploads', $newName);
            $user['picture_path'] = $newName;
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
            'user_name' => $userName
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


}


