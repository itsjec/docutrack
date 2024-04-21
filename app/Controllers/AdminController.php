<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\OfficeModel;
use App\Models\DocumentModel;
use App\Models\OfficeDocumentsModel;
use App\Models\ClassificationModel;
use App\Models\DocumentHistoryModel;


class AdminController extends BaseController
{
    public function __construct()
    {
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        return view('LogIn');
    }

    public function tracking()
    {
        return view('Admin/AdminTracking');
    }

    public function officetracking()
    {
        return view('Admin/AdminOfficeTracking');
    }

    public function admindashboard()
    {
        $db = \Config\Database::connect();
    
        $totalQuery = $db->query("SELECT COUNT(*) AS total FROM documents");
        $totalDocuments = $totalQuery->getRow()->total;
    
        $statusQuery = $db->query("SELECT status, COUNT(*) AS total FROM documents GROUP BY status");
        $statuses = $statusQuery->getResult();
    
        $statusLabels = [];
        $statusCounts = [];
        foreach ($statuses as $status) {
            $statusLabels[] = $status->status;
            $statusCounts[] = $status->total;
        }
    
        $officeQuery = $db->query("SELECT o.office_name, COUNT(d.document_id) AS total FROM documents d
                                    JOIN offices o ON d.recipient_id = o.office_id
                                    GROUP BY d.recipient_id");
        $offices = $officeQuery->getResult();
    
        $officeLabels = [];
        $officeCounts = [];
        foreach ($offices as $office) {
            $officeLabels[] = $office->office_name;
            $officeCounts[] = $office->total;
        }
    
        $userQuery = $db->query("SELECT role, COUNT(*) AS total FROM users GROUP BY role");
        $roles = $userQuery->getResult();
    
        $userLabels = [];
        $userCounts = [];
        foreach ($roles as $role) {
            $userLabels[] = $role->role;
            $userCounts[] = $role->total;
        }
    
        $query = $db->query("SELECT * FROM documents");
        $data['documents'] = $query->getResult();
    
        $data['statusLabels'] = json_encode($statusLabels);
        $data['statusCounts'] = json_encode($statusCounts);
        $data['officeLabels'] = json_encode($officeLabels);
        $data['officeCounts'] = json_encode($officeCounts);
        $data['userLabels'] = json_encode($userLabels);
        $data['userCounts'] = json_encode($userCounts);
        $data['totalDocuments'] = $totalDocuments;
        $data['totalUsers'] = array_sum($userCounts); 

        return view('Admin/AdminDashboard', $data);
    }
    
    

    public function adminmanageoffice()
    {
        $officeModel = new OfficeModel();
        $data['offices'] = $officeModel->findAll();

        return view('Admin/AdminManageOffice', $data);
    }
    
    public function login()
{
    $userModel = new UserModel();

    // Check if the form is submitted
    if ($this->request->getMethod() === 'post') {
        // Get the input data from the form
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Find the user by email
        $user = $userModel->where('email', $email)->first();

        // If user exists and password is correct
        if ($user && password_verify($password, $user['password'])) {
            // Set session data
            $userData = [
                'id' => $user['user_id'],
                'email' => $user['email'],
                'isLoggedIn' => true,
                'role' => $user['role']
            ];

            if ($user['role'] === 'office user') {
                $userData['office_id'] = $user['office_id'];
                $userData['user_id'] = $user['user_id'];
            }

            if ($user['role'] === 'guest') {
                $userData['user_id'] = $user['user_id'];
            }

            session()->set($userData);

            // Redirect based on user role
            switch ($user['role']) {
                case 'admin':
                    return redirect()->to('dashboard');
                    break;
                case 'office user':
                    return redirect()->to('index');
                    break;
                case 'guest':
                default:
                    return redirect()->to('indexloggedin');
                    break;
            }
        } else {
            // Invalid credentials, show error message
            session()->setFlashdata('error', 'Invalid email or password.');
        }
    }

    // Show the login form
    return view('LogIn');
}

    


    
    public function register()
    {
        helper(['form']);
    
        if ($this->request->getMethod() === 'post') {
    
            $rules = [
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|valid_email|is_unique[users.email]',
                'password' => 'required|min_length[8]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/]',
            ];
    
            $errors = [
                'password' => [
                    'regex_match' => 'Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, and one number.'
                ]
            ];
    
            if (!$this->validate($rules, $errors)) {
                $data['validation'] = $this->validator;
            } else {
    
                $imagePath = ''; 
    
                $userData = [
                    'first_name' => $this->request->getVar('first_name'),
                    'last_name' => $this->request->getVar('last_name'),
                    'email' => $this->request->getVar('email'),
                    'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
                    'picture_path' => $imagePath, 
                    'role' => 'admin',
                    'office_id' => null,
                ];
    
                $userModel = new UserModel();
                $userModel->insert($userData);
    
                return redirect()->to('/');
            }
        }
    
        return view('Register');
    }
    

    public function manageoffice()
    {
        $officeModel = new OfficeModel(); 

        $data['offices'] = $officeModel->findAll();

        return view('Admin/AdminManageOffice', $data);
    }

    public function manageguest()
    {
        $userModel = new UserModel();
        $data['guestUsers'] = $userModel->select('first_name, last_name, email, picture_path')
                                        ->where('role', 'guest')
                                        ->findAll();
        
        return view('Admin/AdminManageGuest', $data);
    }
    
    public function saveguest()
    {
        $userModel = new UserModel();
    
        $firstName = $this->request->getPost('firstName');
        $lastName = $this->request->getPost('lastName');
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
    
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
    
        return redirect()->to('manageguest')->with('success', 'Guest user added successfully.');
    }
    

    public function manageuser()
    {
        $userModel = new UserModel();
        $users = $userModel->select('users.*, offices.office_name')
            ->join('offices', 'offices.office_id = users.office_id', 'left')
            ->whereIn('users.role', ['admin', 'office user']) // Filter users by role
            ->findAll();
    
        $officeModel = new OfficeModel();
        $data['offices'] = $officeModel->findAll();
        $data['users'] = $users;
    
        return view('Admin/AdminManageUser', $data); 
    }
    


    public function save()
    {
        $model = new OfficeModel();

        $validation =  \Config\Services::validation();
        $validation->setRules([
            'officeName' => 'required',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $model->save([
            'office_name' => $this->request->getPost('officeName'),
        ]);

        return redirect()->back();
    }
    
    public function saveOfficeUser()
{
    $userModel = new UserModel();
    $officeModel = new OfficeModel();

    $firstName = $this->request->getPost('firstName');
    $lastName = $this->request->getPost('lastName');
    $email = $this->request->getPost('email');
    $password = $this->request->getPost('password');
    $officeId = $this->request->getPost('officeId');

    $office = $officeModel->find($officeId);
    if (!$office) {
        return redirect()->back()->with('error', 'Office not found.');
    }

    $userData = [
        'first_name' => $firstName,
        'last_name' => $lastName,
        'email' => $email,
        'password' => password_hash($password, PASSWORD_DEFAULT),
        'office_id' => $officeId,
        'image' => '',
        'role' => 'office user',
    ];

    $userModel->insert($userData);

    return redirect()->to('manageuser')->with('success', 'Office user added successfully.');
}


public function managedocument()
{
    $userModel = new UserModel();
    $guestUsers = $userModel->where('role', 'guest')->findAll();

    $documentModel = new DocumentModel();
    $documents = $documentModel
        ->select('documents.*, users.first_name, users.last_name, offices.office_name')
        ->join('users', 'users.user_id = documents.sender_id', 'left')
        ->join('offices', 'offices.office_id = documents.recipient_id', 'left')
        ->whereIn('sender_id', array_column($guestUsers, 'user_id'))
        ->findAll();

    $classificationModel = new ClassificationModel();
    $classifications = $classificationModel->distinct()->findColumn('classification_name');
    $classificationsDropdown = array_values($classifications); // Reset array keys to start from 0

    $subClassifications = $classificationModel->findAll();
    $subClassificationsDropdown = array_column($subClassifications, 'sub_classification');

    $officeModel = new OfficeModel();
    $offices = $officeModel->findAll();
    $officesDropdown = [];
    foreach ($offices as $office) {
        $officesDropdown[$office['office_id']] = $office['office_name'];
    }

    $guestUsersNames = [];
    foreach ($guestUsers as $user) {
        $guestUsersNames[$user['user_id']] = $user['first_name'] . ' ' . $user['last_name'];
    }

    $data = [
        'documents' => $documents,
        'classificationsDropdown' => $classificationsDropdown,
        'subClassificationsDropdown' => $subClassificationsDropdown,
        'officesDropdown' => $officesDropdown,
        'guestUsersNames' => $guestUsersNames, // Pass the guest users' names to the view
    ];

    return view('Admin/AdminManageDocument', $data);
}


public function manageofficedocument()
{
    $documentModel = new DocumentModel();
    $documents = $documentModel
        ->select('documents.title, documents.tracking_number, documents.sender_office_id, documents.recipient_id, documents.status, documents.date_of_document, documents.action, sender.office_name AS sender_office_name, recipient.office_name AS recipient_office_name')
        ->join('classification', 'classification.classification_id = documents.classification_id', 'left')
        ->join('offices AS sender', 'sender.office_id = documents.sender_office_id', 'left')
        ->join('offices AS recipient', 'recipient.office_id = documents.recipient_id', 'left')
        ->findAll();

    $classificationModel = new ClassificationModel();
    $classifications = $classificationModel->distinct()->findColumn('classification_name');
    $classificationsDropdown = array_values($classifications); // Reset array keys to start from 0

    $subClassifications = $classificationModel->findAll();
    $subClassificationsDropdown = array_column($subClassifications, 'sub_classification');

    $officeModel = new OfficeModel();
    $offices = $officeModel->findAll();
    $officesDropdown = [];
    foreach ($offices as $office) {
        $officesDropdown[$office['office_id']] = $office['office_name'];
    }

    $data = [
        'documents' => $documents,
        'classificationsDropdown' => $classificationsDropdown,
        'subClassificationsDropdown' => $subClassificationsDropdown,
        'officesDropdown' => $officesDropdown
    ];

    return view('Admin/AdminManageOfficeDocument', $data);
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



public function maintenance()
{
    $classificationModel = new ClassificationModel();

    $classifications = $classificationModel->findAll();

    // Fetch unique classification names for the dropdown
    $distinctClassifications = $classificationModel->distinct()->findColumn('classification_name');

    $classificationsDropdown = [];
    foreach ($distinctClassifications as $classification) {
        $classificationsDropdown[] = $classification;
    }

    $data['classifications'] = $classifications;
    $data['classificationsDropdown'] = $classificationsDropdown;

    return view('Admin/AdminMaintenance', $data);
}


public function saveClassification()
{
    $classificationModel = new ClassificationModel();

    $classificationName = $this->request->getPost('classificationName');

    $data = [
        'classification_name' => $classificationName,
        'sub_classification' => NULL 
    ];

    $classificationModel->insert($data);

    return redirect()->to('maintenance')->with('success', 'Classification added successfully.');
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

    return redirect()->to('maintenance')->with('success', 'Subclassification added successfully.');
}


private function getClassificationName($classificationId)
{
    $classificationModel = new ClassificationModel();
    $classification = $classificationModel->find($classificationId);
    return $classification['classification_name'];
}

public function saveDocument()
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
    $file->move(ROOTPATH . '/uploads', $newName);

    $db = \Config\Database::connect();

    $classification = $this->request->getVar('classification');
    $subClassification = $this->request->getVar('sub_classification');

    try {
        $db->transBegin();

        $trackingNumber = 'TR-' . uniqid();

        $data = [
            'tracking_number' => $trackingNumber,
            'sender_id' => $this->request->getVar('sender_office_id'),
            'sender_office_id' => NULL,
            'recipient_id' => $this->request->getVar('recipient_office_id'),
            'status' => 'pending',
            'title' => $this->request->getVar('title'),
            'description' => $this->request->getVar('description'),
            'action' => $this->request->getVar('action'),
            'date_of_document' => date('Y-m-d'),
            'attachment' => $newName,
            'classification_id' => NULL,
            'classification' => $classification,
            'sub_classification' => $subClassification,
            'date_completed' => NULL
        ];

        $builder = $db->table('documents');
        $builder->insert($data);

        $db->transCommit();

        return redirect()->to(base_url('tracking?trackingNumber=' . $trackingNumber));
    } catch (\Exception $e) {
        $db->transRollback();
        return redirect()->back()->withInput()->with('errors', $e->getMessage());
    }
}


public function saveOfficeDocument()
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

    $documentModel = new \App\Models\DocumentModel();
    $documentModel->insert([
        'tracking_number' => $trackingNumber,
        'sender_id' => NULL,
        'title' => $this->request->getPost('title'),
        'sender_office_id' => $this->request->getPost('sender_office_id'),
        'recipient_id' => $this->request->getPost('recipient_office_id'),
        'status' => 'pending',
        'classification' => $this->request->getPost('classification'),
        'sub_classification' => $this->request->getPost('sub_classification'),
        'date_of_document' => date('Y-m-d'),
        'attachment' => $attachmentName,
        'action' => $this->request->getPost('action'),
        'description' => $this->request->getPost('description'),
        'classification_id' => NULL,
        'date_completed' => NULL
    ]);

    return redirect()->to(base_url('officetracking?trackingNumber=' . $trackingNumber));
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

public function admintransactions()
{
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
        WHERE document_history.status = 'completed'
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
        'senderDetails' => $senderDetails
    ];

    return view('Admin/AdminViewTransactions', $data);
}

public function archived()
{
    $db = db_connect();

    $query = $db->query("
        SELECT 
            document_history.document_id,
            documents.tracking_number, 
            documents.title, 
            CONCAT(users.first_name, ' ', users.last_name) AS deleted_by,
            document_history.date_deleted
        FROM document_history
        JOIN documents ON documents.document_id = document_history.document_id
        JOIN users ON users.user_id = document_history.user_id
        WHERE document_history.status = 'deleted'
    ");

    if (!$query) {
        // Handle error if query fails
        return 'Error: Unable to fetch deleted documents';
    }

    $documents = $query->getResult();

    $data = [
        'documents' => $documents
    ];

    return view('Admin/AdminArchived', $data);
}

    public function documentStatusChart(){
        $db = db_connect();
            $builder = $db->table('documents');
            $builder->select('status, COUNT(*) as count');
            $builder->groupBy('status');
            $query = $builder->get();

            $statusData = $query->getResultArray();

            $labels = [];
            $data = [];

            foreach ($statusData as $row) {
                $labels[] = $row['status'];
                $data[] = $row['count'];
            }

            return view('dashboard', [
                'labels' => json_encode($labels),
                'data' => json_encode($data)
            ]);
        }
}
