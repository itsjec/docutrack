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
        $office = null;
        if ($document && isset($document['recipient_id'])) {
            $office = $officeModel->where('office_id', $document['recipient_id'])->first();
        }

        $progressPercentage = 0;
        if ($document && isset($document['status'])) {
            switch ($document['status']) {
                case 'pending':
                    $progressPercentage = 25;
                    break;
                case 'received':
                    $progressPercentage = 50;
                    break;
                case 'on process':
                    $progressPercentage = 75;
                    break;
                case 'completed':
                    $progressPercentage = 100;
                    break;
            }
        }

        return view('Users/SearchResults', ['document' => $document, 'office' => $office, 'progressPercentage' => $progressPercentage]);
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
        $office = $officeModel->find($document['recipient_id']);

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
        $office = null;
        if ($document && isset($document['recipient_id'])) {
            $office = $officeModel->where('office_id', $document['recipient_id'])->first();
        }

        $progressPercentage = 0;
        if ($document && isset($document['status'])) {
            switch ($document['status']) {
                case 'pending':
                    $progressPercentage = 25;
                    break;
                case 'received':
                    $progressPercentage = 50;
                    break;
                case 'on process':
                    $progressPercentage = 75;
                    break;
                case 'completed':
                    $progressPercentage = 100;
                    break;
            }
        }

        return view('Users/SearchResults', ['document' => $document, 'office' => $office, 'progressPercentage' => $progressPercentage]);
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
                documents.status, 
                (SELECT dh.office_id 
                 FROM document_history dh 
                 WHERE dh.document_id = documents.document_id 
                 ORDER BY dh.date_changed DESC 
                 LIMIT 1) AS current_office 
            FROM documents
            WHERE documents.sender_id = $user_id
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

    public function track()
    {
        $request = \Config\Services::request();
        $trackingNumber = $request->getGet('number');  // Get 'number' from query string

        if (!$trackingNumber) {
            return redirect()->to('/errorPage'); // Redirect to an error page if no tracking number is provided
        }

        $documentModel = new DocumentModel();
        $document = $documentModel->where('tracking_number', $trackingNumber)->first();

        if (!$document) {
            return view('Errors/NoDocument');  // Show an error view if no document is found
        }

        // Assume additional data is fetched like office details, progress percentage etc.
        $officeModel = new OfficeModel();
        $office = $officeModel->find($document['recipient_id']);
        $progressPercentage = 0;
        if ($document && isset($document['status'])) {
            switch ($document['status']) {
                case 'pending':
                    $progressPercentage = 25;
                    break;
                case 'received':
                    $progressPercentage = 50;
                    break;
                case 'on process':
                    $progressPercentage = 75;
                    break;
                case 'completed':
                    $progressPercentage = 100;
                    break;
            }
        }
        $data = [
            'document' => $document,
            'office' => $office,
            'progressPercentage' => $progressPercentage
        ];

        return view('Users/SearchResults', $data);  // Return a view with the document details
    }



}