<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\OfficeModel;
use App\Models\DocumentModel;
use App\Models\DocumentClassificationModel;
use App\Models\OfficeDocumentsModel;

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

    $query = $db->table('offices_documents')
                ->select('documents.title, documents.tracking_number, documents.sender_user_id, documents.sender_office_id, offices_documents.status, documents.action, offices_documents.document_id')
                ->join('documents', 'documents.document_id = offices_documents.document_id')
                ->where('offices_documents.office_id', $office_id)
                ->get();

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
    $data['pending_documents_count'] = $pending_documents_count;
    $data['received_documents_count'] = $received_documents_count;
    $data['total_documents_count'] = $total_documents_count;

    return view('Office/Index', $data);
}

    
    
    public function pending()
    {
        $session = session();
        $office_id = $session->get('office_id');

        $db = db_connect();

        $query = $db->table('offices_documents')
                    ->select('documents.title, documents.tracking_number, documents.sender_user_id, documents.sender_office_id, offices_documents.status, documents.action, offices_documents.document_id')
                    ->join('documents', 'documents.document_id = offices_documents.document_id')
                    ->where('offices_documents.office_id', $office_id)
                    ->where('offices_documents.status', 'pending')
                    ->get();

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
    
        $query = $db->table('offices_documents')
                    ->select('documents.title, documents.tracking_number, documents.sender_user_id, documents.sender_office_id, offices_documents.status, documents.action, offices_documents.document_id')
                    ->join('documents', 'documents.document_id = offices_documents.document_id')
                    ->where('offices_documents.office_id', $office_id)
                    ->where('offices_documents.status', 'incoming')
                    ->get();
    
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

    $query = $db->table('offices_documents')
                ->select('documents.title, documents.tracking_number, documents.sender_user_id, documents.sender_office_id, offices_documents.status, documents.action, offices_documents.document_id')
                ->join('documents', 'documents.document_id = offices_documents.document_id')
                ->where('offices_documents.office_id', $office_id)
                ->where('offices_documents.status', 'on process')
                ->get();

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

    $db = db_connect();

    $query = $db->table('offices_documents')
                ->select('documents.title, documents.tracking_number, documents.sender_user_id, documents.sender_office_id, offices_documents.status, documents.action, offices_documents.document_id')
                ->join('documents', 'documents.document_id = offices_documents.document_id')
                ->where('offices_documents.office_id', $office_id)
                ->where('offices_documents.status', 'completed')
                ->get();

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

    return view('Office/Completed', $data);
}

public function updateStatus() {
    $documentId = $this->request->getPost('document_id');
    $officeId = $this->session->get('office_id');

    $documentsModel = new \App\Models\DocumentModel();
    $officesDocumentsModel = new \App\Models\OfficeDocumentsModel();

    // Start a database transaction
    $db = \Config\Database::connect();
    $db->transStart();

    try {
        // Update status in documents table
        $documentsModel->set('status', 'received')
            ->set('current_office_id', $officeId)
            ->where('document_id', $documentId)
            ->update();

        // Update status in offices_documents table
        $officesDocumentsModel->set('status', 'receive')
            ->where('document_id', $documentId)
            ->update();

        // Commit the transaction
        $db->transCommit();

        // Send a success response
        echo json_encode(['success' => true]);
    } catch (\Exception $e) {
        // Roll back the transaction
        $db->transRollback();

        // Send an error response
        echo json_encode(['success' => false, 'message' => 'An error occurred. Please try again later.']);
    }
}

    public function history()
    {
        return view('Office/History');
    }

    public function manageprofile()
    {
        return view('Office/ManageProfile');
    }

    public function trash()
    {
        return view('Office/Trash');
    }
}
