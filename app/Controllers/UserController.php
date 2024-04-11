<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\OfficeModel;
use App\Models\DocumentModel;
use App\Models\DocumentClassificationModel;
use App\Models\DocumentHistoryModel;

class UserController extends BaseController
{
    public function index()
    {
        return view('Users/Index');
    }

    public function indexloggedin()
    {
        return view('LoggedIn/Index');
    }

    public function searchResults()
    {
        $request = \Config\Services::request();
        $trackingNumber = $request->getPost('tracking_number');

        $documentModel = new DocumentModel();
        $document = $documentModel->where('tracking_number', $trackingNumber)->first();

        $officeModel = new OfficeModel();
        $office = $officeModel->where('office_id', $document['recipient'])->first();

        return view('Users/SearchResults', ['document' => $document, 'office' => $office]);
    }

    public function viewdetails()
    {
        $trackingNumber = $this->request->getVar('tracking_number');
    
        $documentModel = new DocumentModel();
        $document = $documentModel->where('tracking_number', $trackingNumber)->first();
    
        $workflowModel = new DocumentHistoryModel();
        $workflow_history = $workflowModel->where('document_id', $document['document_id'])->findAll();
    
        $adminModel = new UserModel();
        $admins = $adminModel->findAll();
    
        $officeModel = new OfficeModel(); 
        $office = $officeModel->find($document['recipient']);
    
        $data = [
            'tracking_number' => $trackingNumber,
            'workflow_history' => $workflow_history,
            'admins' => $admins,
            'office' => $office,
        ];        
    
        return view('Users/ViewDetails', $data);
    }

    public function guestsearchResults()
    {
        $request = \Config\Services::request();
        $trackingNumber = $request->getPost('tracking_number');

        $documentModel = new DocumentModel();
        $document = $documentModel->where('tracking_number', $trackingNumber)->first();

        $officeModel = new OfficeModel();
        $office = $officeModel->where('office_id', $document['recipient_id'])->first();

        return view('LoggedIn/SearchResults', ['document' => $document, 'office' => $office]);
    }

    public function guestviewdetails()
    {
        $trackingNumber = $this->request->getVar('tracking_number');
    
        $documentModel = new DocumentModel();
        $document = $documentModel->where('tracking_number', $trackingNumber)->first();
    
        $workflowModel = new DocumentHistoryModel();
        $workflow_history = $workflowModel->where('document_id', $document['document_id'])->findAll();
    
        $adminModel = new UserModel();
        $admins = $adminModel->findAll();
    
        $officeModel = new OfficeModel(); 
        $office = $officeModel->find($document['recipient']);
    
        $data = [
            'tracking_number' => $trackingNumber,
            'workflow_history' => $workflow_history,
            'admins' => $admins,
            'office' => $office,
        ];        
    
        return view('LoggedIn/ViewDetails', $data);
    }

    public function transaction()
    {
        $session = session();
        $user_id = $session->get('user_id');
    
        if (!$user_id) {
            return 'Error: User ID not set';
        }
    
        $db = db_connect();
    
        $query = $db->query("
            SELECT 
                documents.title, 
                documents.tracking_number,
                document_history.status,
                offices.office_name as current_office
            FROM documents
            JOIN (
                SELECT dh.*
                FROM document_history dh
                JOIN (
                    SELECT document_id, MAX(date_changed) AS max_date_changed
                    FROM document_history
                    WHERE user_id = $user_id
                    GROUP BY document_id
                ) AS latest ON dh.document_id = latest.document_id AND dh.date_changed = latest.max_date_changed
            ) AS document_history ON documents.document_id = document_history.document_id
            JOIN offices ON document_history.office_id = offices.office_id
        ");
    
        if (!$query) {
            return 'Error: Unable to fetch transaction history';
        }
    
        $documents = $query->getResult();
    
        $data = [
            'documents' => $documents
        ];
    
        return view('LoggedIn/Transactions', $data);
    }
    
}