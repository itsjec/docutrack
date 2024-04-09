<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\OfficeModel;
use App\Models\DocumentModel;
use App\Models\DocumentClassificationModel;
use App\Models\OfficeDocumentsModel;
use App\Models\TransactionModel;
use App\Models\DocumentHistoryModel;

class OfficeController extends BaseController
{


    public function __construct()
    {
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        $officeId = session('office_id');
        echo "Office ID: " . $officeId . "<br>";
    
        $documentModel = new \App\Models\DocumentModel();
        $documents = $documentModel->findAll();
        echo "Number of documents: " . count($documents) . "<br>";
    
        $db = \Config\Database::connect();
    
        // Count pending documents
        $pending_documents_count = $db->table('documents')
                                      ->where('recipient_id', $officeId)
                                      ->where('status', 'pending')
                                      ->countAllResults();
    
        // Count received documents
        $received_documents_count = $db->table('documents')
                                       ->where('recipient_id', $officeId)
                                       ->where('status', 'received')
                                       ->countAllResults();
    
        // Count total documents for the office
        $total_documents_count = $db->table('documents')
                                     ->where('recipient_id', $officeId)
                                     ->countAllResults();
    
        $documents = $db->table('documents')
                        ->select('documents.*, offices.office_name as sender_office_name')
                        ->join('offices', 'offices.office_id = documents.sender_office_id')
                        ->where('recipient_id', $officeId)
                        ->get()
                        ->getResult();
    
        echo "Number of filtered documents: " . count($documents) . "<br>";
    
        return view('Office/Index', [
            'documents' => $documents,
            'pending_documents_count' => $pending_documents_count,
            'received_documents_count' => $received_documents_count,
            'total_documents_count' => $total_documents_count
        ]);
    }
    
    
    public function updateDocumentStatus($documentId, $newStatus)
    {
        $workflowModel = new DocumentHistoryModel();

        $userId = session()->get('user_id');

        $officeId = session()->get('office_id');

        $data = [
            'document_id' => $documentId,
            'user_id' => $userId,
            'office_id' => $officeId,
            'status' => $newStatus,
            'date_changed' => date('Y-m-d H:i:s'),
            'is_admin_view' => 0,
            'is_completed' => ($newStatus == 'completed') ? 1 : 0
        ];

        $workflowModel->insert($data);

        return redirect()->back();
    }

    
    public function pending()
    {
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
            $sender_user_id = $document->sender_user_id;
            $sender_office_id = $document->sender_office_id;
    
            $senderUserModel = new UserModel();
            $senderUser = $senderUserModel->find($sender_user_id);
    
            $senderOfficeModel = new OfficeModel();
            $senderOffice = $senderOfficeModel->find($sender_office_id);
    
            $senderDetails[$document->document_id] = [
                'sender_user' => $senderUser['first_name'] . ' ' . $senderUser['last_name'],
                'sender_office' => $senderOffice['office_name']
            ];
        }
    
        $data['documents'] = $documents;
        $data['senderDetails'] = $senderDetails;
    
        return view('Office/Pending', $data);
    }
    

    public function incoming()
    {
        $session = session();
        $office_id = $session->get('office_id');
    
        $db = db_connect();
    
        $query = $db->query("
            SELECT 
                DISTINCT documents.title, 
                documents.tracking_number, 
                documents.sender_user_id, 
                documents.sender_office_id, 
                offices_documents.status, 
                documents.action, 
                offices_documents.document_id
            FROM offices_documents
            JOIN documents ON documents.document_id = offices_documents.document_id
            WHERE offices_documents.office_id = $office_id
            AND offices_documents.status = 'incoming'
        ");
    
        $documents = $query->getResult();
    
        $senderDetails = [];
        foreach ($documents as $document) {
            $sender_user_id = $document->sender_user_id;
            $sender_office_id = $document->sender_office_id;
    
            $senderUserModel = new UserModel();
            $senderUser = $senderUserModel->find($sender_user_id);
    
            $senderOfficeModel = new OfficeModel();
            $senderOffice = $senderOfficeModel->find($sender_office_id);
    
            $senderDetails[$document->document_id] = [
                'sender_user' => $senderUser['first_name'] . ' ' . $senderUser['last_name'],
                'sender_office' => $senderOffice['office_name']
            ];
        }
    
        $data['documents'] = $documents;
        $data['senderDetails'] = $senderDetails;
    
        return view('Office/Incoming', $data);
    }
    
    
    public function ongoing()
    {
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
            $sender_user_id = $document->sender_user_id;
            $sender_office_id = $document->sender_office_id;
    
            $senderUserModel = new UserModel();
            $senderUser = $senderUserModel->find($sender_user_id);
    
            $senderOfficeModel = new OfficeModel();
            $senderOffice = $senderOfficeModel->find($sender_office_id);
    
            $senderDetails[$document->document_id] = [
                'sender_user' => $senderUser['first_name'] . ' ' . $senderUser['last_name'],
                'sender_office' => $senderOffice['office_name']
            ];
        }
    
        $data['documents'] = $documents;
        $data['senderDetails'] = $senderDetails;
    
        return view('Office/Ongoing', $data);
    }
    

    public function received()
    {
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
            $sender_user_id = $document->sender_user_id;
            $sender_office_id = $document->sender_office_id;
    
            $senderUserModel = new UserModel();
            $senderUser = $senderUserModel->find($sender_user_id);
    
            $senderOfficeModel = new OfficeModel();
            $senderOffice = $senderOfficeModel->find($sender_office_id);
    
            $senderDetails[$document->document_id] = [
                'sender_user' => $senderUser['first_name'] . ' ' . $senderUser['last_name'],
                'sender_office' => $senderOffice['office_name']
            ];
        }
    
        $data['documents'] = $documents;
        $data['senderDetails'] = $senderDetails;
    
        return view('Office/Received', $data);
    }
    

    public function completed()
{
    $session = session();
    $office_id = $session->get('office_id');

    if (!$office_id) {
        // Handle error if office_id is not set in the session
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
        $sender_user_id = $document->sender_user_id;
        $sender_office_id = $document->sender_office_id;

        $senderUserModel = new UserModel();
        $senderUser = $senderUserModel->find($sender_user_id);

        $senderOfficeModel = new OfficeModel();
        $senderOffice = $senderOfficeModel->find($sender_office_id);

        if (!$senderUser || !$senderOffice) {
            // Handle error if sender details not found
            return 'Error: Sender details not found';
        }

        $senderDetails[$document->document_id] = [
            'sender_user' => $senderUser['first_name'] . ' ' . $senderUser['last_name'],
            'sender_office' => $senderOffice['office_name']
        ];
    }

    $data = [
        'offices' => $offices,
        'documents' => $documents,
        'senderDetails' => $senderDetails
    ];

    return view('Office/Completed', $data);
}


public function updateStatusToReceived($documentId)
{
    $db = db_connect();

    $documentModel = new \App\Models\DocumentModel();

    // Find the document by ID
    $document = $documentModel->find($documentId);

    if (!$document) {
        return "Document not found";
    }

    // Update the status to "received"
    $updated = $documentModel->update($documentId, ['status' => 'received']);

    var_dump($updated); // Check if the update was successful

    $updatedDocument = $documentModel->find($documentId);
    var_dump($updatedDocument); // Check the updated document

    if ($updated && $updatedDocument && $updatedDocument['status'] === 'received') {
        return "Status updated successfully";
    } else {
        return "Failed to update status to 'received'";
    }
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

    public function updateProcessStatus()
    {
        $documentId = $this->request->getPost('document_id');
        $userId = session()->get('id'); // Assuming 'id' is the key for user_id in the session
        $currentOfficeId = session()->get('office_id');
    
        $db = \Config\Database::connect();
    
        $db->transStart();
    
        try {
            // Update offices_documents table
            $db->table('offices_documents')
                ->where('document_id', $documentId)
                ->where('office_id', $currentOfficeId) // Add this condition
                ->update(['status' => 'on process']);
    
            // Update documents table
            $db->table('documents')
                ->where('document_id', $documentId)
                ->update(['status' => 'on process']);
    
            // Insert into transactions table
            $data = [
                'document_id' => $documentId,
                'status' => 'on process',
                'user_id' => $userId,
                'date' => date('Y-m-d H:i:s'),
                'date_completed' => null,
                'previous_office_id' => null,
                'current_office_id' => $currentOfficeId
            ];
            $db->table('transactions')->insert($data);
    
            $db->transCommit();
    
            return 'Status updated successfully';
        } catch (\Exception $e) {
            $db->transRollback();
            return 'Error: ' . $e->getMessage();
        }
    }
    

    public function updateCompletedStatus()
    {
        $documentId = $this->request->getPost('document_id');
        $userId = session()->get('id'); // Assuming 'id' is the key for user_id in the session
        $currentOfficeId = session()->get('office_id'); // Assuming 'office_id' is the key for office_id in the session
    
        $db = \Config\Database::connect();
    
        $db->transStart();
    
        try {
            // Update offices_documents table
            $db->table('offices_documents')
                ->where('document_id', $documentId)
                ->where('office_id', $currentOfficeId) // Add this condition
                ->update(['status' => 'completed', 'date_completed' => date('Y-m-d H:i:s')]);
    
            // Update documents table
            $db->table('documents')
                ->where('document_id', $documentId)
                ->update(['status' => 'completed']);
    
            $db->transCommit();
    
            return 'Status updated successfully';
        } catch (\Exception $e) {
            $db->transRollback();
            return 'Error: ' . $e->getMessage();
        }
    }
    
       

    public function deleteDocument()
    {
        $documentId = $this->request->getPost('document_id');
    
        $db = \Config\Database::connect();
    
        $db->transStart();
    
        try {
            // Update offices_documents table
            $db->table('offices_documents')
               ->where('document_id', $documentId)
               ->update(['status' => 'deleted', 'date_deleted' => date('Y-m-d H:i:s')]);
    
            // Update documents table
            $db->table('documents')
               ->where('document_id', $documentId)
               ->update(['status' => 'deleted']);
    
            $db->transCommit();
    
            return 'Document deleted successfully';
        } catch (\Exception $e) {
            $db->transRollback();
            return 'Error: ' . $e->getMessage();
        }
    }
    

    public function sendOutDocument()
    {
        $document_id = $this->request->getPost('document_id');
        $office_id = $this->request->getPost('office_id');
    
        $sender_office_id = session()->get('office_id');
        $user_id = session()->get('user_id');
    
        $db = \Config\Database::connect();
    
        $db->transStart();
    
        try {
            // Update sender's office document status to "Finished" in the offices_documents table
            $db->table('offices_documents')
                ->where('document_id', $document_id)
                ->where('office_id', $sender_office_id)
                ->update(['status' => 'completed']);
    
            // Insert a new row for the receiving office with "incoming" status in the offices_documents table
            $officeDocumentModel = new OfficeDocumentsModel();
            $officeDocumentModel->insert([
                'document_id' => $document_id,
                'office_id' => $office_id,
                'status' => 'incoming'
            ]);
    
            $date = date('Y-m-d H:i:s');
            $data = [
                'document_id' => $document_id,
                'status' => 'incoming',
                'user_id' => $user_id,
                'date' => $date,
                'date_completed' => $date, 
                'previous_office_id' => $sender_office_id,
                'current_office_id' => $office_id
            ];
            $db->table('transactions')->insert($data);
    
            $db->transCommit();
    
            return 'Document sent successfully';
        } catch (\Exception $e) {
            $db->transRollback();
            return 'Error: ' . $e->getMessage();
        }
    }
    
    public function history()
    {
        $session = session();
        $office_id = $session->get('office_id');
    
        if (!$office_id) {
            // Handle error if office_id is not set in the session
            return 'Error: Office ID not set';
        }
    
        $db = db_connect();
    
        $query = $db->query("
    SELECT 
        documents.document_id,
        documents.title, 
        documents.tracking_number, 
        documents.sender_office_id,
        documents.current_office_id,
        offices_documents.status,
        MAX(offices_documents.date_completed) AS date_completed,
        offices.office_name AS current_office_name
    FROM offices_documents
    JOIN documents ON documents.document_id = offices_documents.document_id
    JOIN offices ON offices.office_id = documents.current_office_id
    WHERE offices_documents.office_id = $office_id
    AND offices_documents.status = 'completed'
    GROUP BY documents.document_id
");

    
        if (!$query) {
            // Handle error if query fails
            return 'Error: Unable to fetch completed documents';
        }
    
        $documents = $query->getResult();
    
        $data = [
            'documents' => $documents
        ];
    
        return view('Office/History', $data);
    }
      

    public function manageprofile()
    {
        return view('Office/ManageProfile');
    }

    public function trash()
    {
        $session = session();
        $office_id = $session->get('office_id');
    
        if (!$office_id) {
            // Handle error if office_id is not set in the session
            return 'Error: Office ID not set';
        }
    
        $db = db_connect();
    
        $query = $db->query("
            SELECT 
                DISTINCT documents.document_id,
                documents.title, 
                documents.tracking_number, 
                documents.description,
                documents.date_of_letter,
                offices_documents.date_deleted,
                offices.office_name AS current_office_name
            FROM offices_documents
            JOIN documents ON documents.document_id = offices_documents.document_id
            JOIN offices ON offices.office_id = documents.current_office_id
            WHERE offices_documents.office_id = $office_id
            AND offices_documents.status = 'deleted'
        ");
    
        if (!$query) {
            // Handle error if query fails
            return 'Error: Unable to fetch deleted documents';
        }
    
        $documents = $query->getResult();
    
        $data = [
            'documents' => $documents
        ];
    
        return view('Office/Trash', $data);
    }
    
}
