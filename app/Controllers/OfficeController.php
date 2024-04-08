<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\OfficeModel;
use App\Models\DocumentModel;
use App\Models\DocumentClassificationModel;
use App\Models\OfficeDocumentsModel;
use App\Models\TransactionModel;
use App\Models\WorkflowModel;

class OfficeController extends BaseController
{
    public function index()
    {
        $session = session();
        $office_id = $session->get('office_id');
    
        $db = db_connect();
    
        // Count pending documents
        $query_pending = $db->table('offices_documents')
                            ->select('COUNT(*) as count')
                            ->where('office_id', $office_id)
                            ->where('status', 'incoming')
                            ->get();
        $result_pending = $query_pending->getRow();
        $pending_documents_count = $result_pending ? $result_pending->count : 0;
    
        // Count received documents
        $query_received = $db->table('offices_documents')
                            ->select('COUNT(*) as count')
                            ->where('office_id', $office_id)
                            ->where('status', 'pending')
                            ->get();
        $result_received = $query_received->getRow();
        $received_documents_count = $result_received ? $result_received->count : 0;
    
        // Count total documents for the office
        $query_total = $db->table('offices_documents')
                            ->select('COUNT(*) as count')
                            ->where('office_id', $office_id)
                            ->get();
        $result_total = $query_total->getRow();
        $total_documents_count = $result_total ? $result_total->count : 0;
    
        // Fetch documents with sender details
        $query = $db->query("
            SELECT 
                documents.title, 
                documents.tracking_number, 
                documents.sender_user_id, 
                documents.sender_office_id, 
                MAX(offices_documents.status) AS status, 
                documents.action, 
                offices_documents.document_id
            FROM offices_documents
            JOIN documents ON documents.document_id = offices_documents.document_id
            WHERE offices_documents.office_id = $office_id
            GROUP BY offices_documents.document_id
        ");
    
        $documents = $query->getResult();
    
        $senderUserIds = array_column($documents, 'sender_user_id');
        $senderOfficeIds = array_column($documents, 'sender_office_id');
    
        $senderUserModel = new UserModel();
        $senderOfficeModel = new OfficeModel();
    
        $senderUsers = $senderUserModel->find($senderUserIds);
        $senderOffices = $senderOfficeModel->find($senderOfficeIds);
    
        $senderDetails = [];
        foreach ($documents as $document) {
            $sender_user_id = $document->sender_user_id;
            $sender_office_id = $document->sender_office_id;
    
            $senderDetails[$document->document_id] = [
                'sender_user' => $senderUsers[$sender_user_id]['first_name'] . ' ' . $senderUsers[$sender_user_id]['last_name'],
                'sender_office' => $senderOffices[$sender_office_id]['office_name']
            ];
        }
    
        $data = [
            'documents' => $documents,
            'senderDetails' => $senderDetails,
            'pending_documents_count' => $pending_documents_count,
            'received_documents_count' => $received_documents_count,
            'total_documents_count' => $total_documents_count
        ];
    
        return view('Office/Index', $data);
    }
    

    
    
public function pending()
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
        AND offices_documents.status = 'pending'
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
            AND offices_documents.status = 'on process'
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
            AND offices_documents.status = 'completed'
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
    


    public function updateStatus()
    {
        $documentId = $this->request->getPost('document_id');
        $currentOfficeId = session()->get('office_id'); // Assuming office_id is stored in the session
    
        $db = \Config\Database::connect();
    
        $db->transStart();
    
        try {
            $db->table('offices_documents')
                ->where('document_id', $documentId)
                ->where('office_id', $currentOfficeId) // Add this condition
                ->update(['status' => 'pending']);
    
            // Update documents table
            $db->table('documents')
                ->where('document_id', $documentId)
                ->update(['status' => 'received', 'current_office_id' => $currentOfficeId]);
    
            $data = [
                'document_id' => $documentId,
                'office_id' => $currentOfficeId,
                'admin_id' => session()->get('user_id'), // Assuming 'user_id' is the key for user_id in the session
                'status' => 'received',
                'date_changed' => date('Y-m-d H:i:s')
            ];
            $db->table('all_offices_workflow')->insert($data);
    
            $db->transCommit();
    
            return 'Status updated successfully';
        } catch (\Exception $e) {
            $db->transRollback();
            return 'Error: ' . $e->getMessage();
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
      
    
    
    

    public function updateDocumentStatus($documentId, $newStatus)
    {
        $documentModel = new DocumentModel(); 
        $workflowModel = new WorkflowModel();

        $currentStatus = $documentModel->getStatus($documentId);

        $documentModel->update($documentId, ['status' => $newStatus]);

        $adminId = session()->get('user_id'); 

        $data = [
            'document_id' => $documentId,
            'office_id' => session()->get('office_id'), // Assuming 'office_id' is the key for office_id in the session
            'user_id' => $adminId,
            'status' => $newStatus,
            'date_changed' => date('Y-m-d H:i:s')
        ];
        $workflowModel->insert($data);

        return redirect()->back();
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
