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

    public function loggedinsearch()
    {
        return view('LoggedIn/Search');
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
        $document = $documentModel->select('tracking_number, title')
        ->where('tracking_number', $trackingNumber)
        ->first();


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
    
        // Fetch the document based on the tracking number
        $documentModel = new DocumentModel();
        $document = $documentModel->where('tracking_number', $trackingNumber)->first();
    
        if (!$document) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Document not found");
        }
    
        // Fetch the workflow history for the document
        $workflowModel = new DocumentHistoryModel();
        $workflow_history = $workflowModel->where('document_id', $document['document_id'])->findAll();
    
        // Fetch all users
        $adminModel = new UserModel();
        $admins = $adminModel->findAll();
    
        // Fetch all offices
        $officeModel = new OfficeModel();
        $offices = $officeModel->findAll();
    
        // Map users and offices by ID for easy access
        $userMap = [];
        foreach ($admins as $admin) {
            $userMap[$admin['user_id']] = $admin;
        }
    
        $officeMap = [];
        foreach ($offices as $office) {
            $officeMap[$office['office_id']] = $office;
        }
    
        // Fetch the recipient's office details
        $recipientOffice = $officeModel->find($document['recipient_id']);
    
        // Prepare data for the view
        $data = [
            'tracking_number' => $trackingNumber,
            'workflow_history' => $workflow_history,
            'title' => $document['title'],
            'admins' => $userMap, // Use the mapped user data
            'offices' => $officeMap, // Use the mapped office data
            'recipient_office' => $recipientOffice ? $recipientOffice['office_name'] : 'Unknown Office',
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
    
        $documentModel = new DocumentModel();
        $documents = $documentModel
            ->select('documents.*, offices.office_name') // Select all columns from documents and office_name from offices
            ->join('offices', 'offices.office_id = documents.recipient_id') // Join offices table using recipient_id
            ->where('sender_id', $user_id)
            ->orderBy('date_of_document', 'desc')
            ->get()
            ->getResultArray();
    
        if (empty($documents)) {
            return 'No documents found for the current user';
        }
    
        // Log the query result for debugging
        log_message('debug', 'Query result: ' . print_r($documents, true));
    
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