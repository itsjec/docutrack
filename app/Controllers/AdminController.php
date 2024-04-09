<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\OfficeModel;
use App\Models\DocumentModel;
use App\Models\OfficeDocumentsModel;
use App\Models\ClassificationModel;
use App\Models\SubClassificationModel;

class AdminController extends BaseController
{
    public function index()
    {
        return view('LogIn');
    }

    public function tracking()
    {
        return view('Admin/TrackingNumber');
    }

    public function admindashboard()
    {
        return view('Admin/AdminDashboard');
    }

    public function adminmanageoffice()
    {
        $officeModel = new OfficeModel();
        $data['offices'] = $officeModel->findAll();

        return view('Admin/AdminManageOffice', $data);
    }

    public function __construct()
    {
        $this->session = \Config\Services::session();
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
                    'id' => $user['id'],
                    'email' => $user['email'],
                    'isLoggedIn' => true,
                    'role' => $user['role']
                ];
    
                // If the user is an office_user, store the office_id and user_id in the session
                if ($user['role'] === 'office_user') {
                    $userData['office_id'] = $user['office_id'];
                    $userData['user_id'] = $user['id'];
                }
    
                // Set session
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
                        return redirect()->to('');
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
    $documentModel = new DocumentModel();
    $documents = $documentModel->select('documents.*, offices.office_name AS receiver_office_name')
                               ->join('offices', 'offices.office_id = documents.receiver_office_id')
                               ->findAll();

    $userModel = new UserModel();
    $guestUsers = $userModel->where('role', 'guest')->findAll();

    $officeModel = new OfficeModel();
    $offices = $officeModel->findAll();

    // Fetch sender's first name and last name
    foreach ($documents as &$document) {
        $user = $userModel->find($document['sender_user_id']);
        if ($user) {
            $document['sender_first_name'] = $user['first_name'];
            $document['sender_last_name'] = $user['last_name'];
        } else {
            $document['sender_first_name'] = 'Unknown';
            $document['sender_last_name'] = 'User';
        }
    }

    $classificationModel = new DocumentClassificationModel();
    $classifications = $classificationModel->findAll();
    $uniqueClassifications = array_unique(array_column($classifications, 'classification'));

    $data = [
        'documents' => $documents,
        'guestUsers' => $guestUsers,
        'offices' => $offices,
        'classifications' => $uniqueClassifications,
        'selectedSubClassification' => ''
    ];

    return view('Admin/AdminManageDocument', $data);
}



public function manageofficedocument()
{
    $documentModel = new DocumentModel();
    $documents = $documentModel
        ->select('documents.title, documents.tracking_number, documents.sender_id, documents.recipient_id, documents.status, documents.date_of_document, documents.action, classification.classification_name AS classification, classification.sub_classification AS sub_classification, offices.office_name AS sender_office_name')
        ->join('classification', 'classification.classification_id = documents.classification_id', 'left')
        ->join('offices', 'offices.office_id = documents.sender_id', 'left')
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
        'sender_user_id' => 'required',
        'receiver_office_id' => 'required'
    ];

    if (!$this->validate($validationRules)) {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    } else {
        $file = $this->request->getFile('attachment');
        if ($file->isValid() && $file->getClientMimeType() === 'application/pdf') {
            $newName = $file->getRandomName();
            $file->move(ROOTPATH . '/uploads', $newName);

            $documentModel = new DocumentModel();

            $data = [
                'tracking_number' => 'TR-' . uniqid(),
                'sender_user_id' => $this->request->getVar('sender_user_id'),
                'sender_office_id' => null,
                'receiver_office_id' => $this->request->getVar('receiver_office_id'),
                'current_office_id' => null,
                'status' => 'pending',
                'title' => $this->request->getVar('title'),
                'description' => $this->request->getVar('description'),
                'action' => $this->request->getVar('action'),
                'date_of_letter' => date('Y-m-d'),
                'attachment' => $newName,
                'classification' => $this->request->getVar('classification'),
                'sub_classification' => $this->request->getVar('sub_classification')
            ];

            $documentModel->insert($data);

            $officeDocumentModel = new OfficeDocumentsModel();
            $office_id = $this->request->getVar('receiver_office_id');
            $officeDocumentModel->insert([
                'document_id' => $documentModel->getInsertID(),
                'office_id' => $office_id,
                'status' => 'incoming'
            ]);

            $response = [
                'status' => 'success',
                'trackingNumber' => $data['tracking_number']
            ];

            return $this->response->setJSON($response);
        } else {
            return redirect()->back()->withInput()->with('error', 'Invalid file. Please upload a PDF file.');
        }
    }
}


public function saveOfficeDocument()
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
    } else {
        $file = $this->request->getFile('attachment');
        if ($file->isValid() && $file->getClientMimeType() === 'application/pdf') {
            $newName = $file->getRandomName();
            $file->move(ROOTPATH . '/uploads', $newName);

            // Connect to the database
            $db = \Config\Database::connect();

            // Debugging: Check the values of classification and sub_classification
            $classification = $this->request->getVar('classification');
            $subClassification = $this->request->getVar('sub_classification');
            var_dump($classification);
            var_dump($subClassification);

            $data = [
                'tracking_number' => 'TR-' . uniqid(),
                'sender_id' => NULL,
                'sender_office_id' => $this->request->getVar('sender_office_id'),
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

            $response = [
                'status' => 'success',
                'trackingNumber' => $data['tracking_number']
            ];
            session()->setFlashdata('success', 'Document added successfully.');
            session()->setFlashdata('trackingNumber', $data['tracking_number']);
            return $this->response->setJSON($response);   
        }         
    }
}


public function testInsert()
{
    $result = $this->saveOfficeDocument();

    echo $result;
}



public function debug(){
    return view('Admin/Debug');
}

}