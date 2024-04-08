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

    if (!$office_id) {
        // Handle error if office_id is not set in the session
        return 'Error: Office ID not set';
    }

    $db = db_connect();

    // Fetch the offices
    $officesModel = new OfficeModel();
    $offices = $officesModel->findAll();

    $query = $db->table('offices_documents')
                ->select('documents.title, documents.tracking_number, documents.sender_user_id, documents.sender_office_id, offices_documents.status, documents.action, offices_documents.document_id')
                ->join('documents', 'documents.document_id = offices_documents.document_id')
                ->where('offices_documents.office_id', $office_id)
                ->where('offices_documents.status', 'completed')
                ->get();

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

        $db = \Config\Database::connect();

        $db->transStart();

        try {
            $db->table('offices_documents')
            ->where('document_id', $documentId)
            ->update(['status' => 'pending']);

            $db->table('documents')
            ->where('document_id', $documentId)
            ->update(['status' => 'received', 'current_office_id' => $this->getCurrentOfficeId($documentId)]);

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

        $db = \Config\Database::connect();

        $db->transStart();

        try {
            $db->table('offices_documents')
            ->where('document_id', $documentId)
            ->update(['status' => 'on process']);

            $db->table('documents')
            ->where('document_id', $documentId)
            ->update(['status' => 'on process']);

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

        $db = \Config\Database::connect();

        $db->transStart();

        try {
            $db->table('offices_documents')
            ->where('document_id', $documentId)
            ->update(['status' => 'completed']);

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
        $db->table('offices_documents')
           ->where('document_id', $documentId)
           ->update(['status' => 'deleted']);

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

    $db = \Config\Database::connect();
    $builder = $db->table('documents');
    $builder->where('document_id', $document_id);
    $builder->update(['sender_office_id' => $sender_office_id]);

    $officeDocumentModel = new OfficeDocumentsModel();
    $officeDocumentModel->insert([
        'document_id' => $document_id,
        'office_id' => $office_id,
        'status' => 'incoming'
    ]);

    return 'Document sent successfully';
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
