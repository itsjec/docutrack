<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\OfficeModel;
use App\Models\DocumentModel;
use App\Models\OfficeDocumentsModel;
use App\Models\ClassificationModel;
use App\Models\DocumentHistoryModel;
use SimpleSoftwareIO\QrCode\Generator;
use DateTime;
use CodeIgniter\I18n\Time;

class AdminController extends BaseController
{
    public function __construct()
    {
        $this->session = \Config\Services::session();
    }

    private $key = 'docutrack0129scrtKeY'; 


    public function index()
    {
        return view('LogIn');
    }

    public function tracking()
    {

        return view('Admin/AdminTracking');
    }

    public function reset(){
        return view('Reset');
    }

    public function activitytracker()
    {
        $documentModel = new DocumentModel();
        $documents = $documentModel
            ->select('documents.document_id, documents.title, documents.tracking_number, documents.status, documents.date_of_document, 
                      sender.office_name AS sender_office_name, documents.sender_office_id, sender_id, 
                      users.first_name, users.last_name, documents.date_completed') // Include date_completed
            ->join('offices sender', 'sender.office_id = documents.sender_office_id', 'left')
            ->join('users', 'users.user_id = documents.sender_id', 'left')
            ->where('documents.status !=', 'deleted')
            ->orderBy('documents.date_of_document', 'DESC')
            ->findAll();
    
        $db = \Config\Database::connect();
    
        $documentsWithHistory = [];
    
        foreach ($documents as $document) {
            $history = $db->table('document_history')
                ->select('document_history.status, document_history.date_changed, document_history.office_id, offices.office_name, users.first_name AS modified_first_name, users.last_name AS modified_last_name')
                ->join('offices', 'document_history.office_id = offices.office_id')
                ->join('users', 'document_history.user_id = users.user_id')
                ->where('document_history.document_id', $document['document_id'])
                ->orderBy('document_history.date_changed')
                ->get()
                ->getResultArray();
    
            // Organize the history by office
            $historyByOffice = [];
            foreach ($history as $record) {
                $officeName = htmlspecialchars($record['office_name']);
                $historyByOffice[$officeName][] = [
                    'status' => htmlspecialchars($record['status']),
                    'date_changed' => $record['date_changed'],
                    'modified_first_name' => htmlspecialchars($record['modified_first_name']),
                    'modified_last_name' => htmlspecialchars($record['modified_last_name']),
                ];
            }
    
            // Append document data along with its organized history
            $document['history'] = $historyByOffice;
            $document['office_name'] = htmlspecialchars($document['sender_office_name']);
            $documentsWithHistory[] = $document;
        }
    
        $data = [
            'documents' => $documentsWithHistory,
        ];
    
        return view('Admin/AdminActivityTracker', $data);
    }
    
    
    public function officetracking()
    {
        return view('Admin/AdminOfficeTracking');
    }
    public function admindashboard()
    {
        $db = \Config\Database::connect();

        $currentDate = date('Y-m-d');

        $query = $db->query("SELECT document_id, date_of_document FROM documents");
        $documents = $query->getResult();

        $documentAgesDays = [];
        $documentAgesMonths = [];
        $documentLabels = [];
        $currentDate = new DateTime();

        foreach ($documents as $document) {
            $dateOfDocument = new DateTime($document->date_of_document);
            $diff = $dateOfDocument->diff($currentDate);
            $days = $diff->days;

            $months = floor($days / 30);

            $documentAgesDays[$document->document_id] = $days;
            $documentAgesMonths[$document->document_id] = $months;
            $documentLabels[$document->document_id] = "D" . $document->document_id; // Label with "D" prefix
        }

        $totalQuery = $db->query("SELECT COUNT(*) AS total FROM documents");
        $totalDocuments = $totalQuery->getRow()->total;

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

        $query = $db->query('SELECT COUNT(*) as totalOffices FROM offices'); 
        $result = $query->getRow();

        $totalOffices = $result->totalOffices; 

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

        $totalStatuses = count($statusLabels);

        $query = $db->query("
            SELECT 
                document_history.office_id AS current_office_id,
                tp.received_timestamp,
                tp.completed_timestamp
            FROM document_history
            LEFT JOIN document_timeprocessing tp ON document_history.document_id = tp.document_id AND document_history.office_id = tp.office_id
            WHERE document_history.status = 'completed'
        ");

        $documents = $query->getResult();

        $processingTimesByOffice = [];
        $documentCountsByOffice = [];

        foreach ($documents as $document) {
            if ($document->received_timestamp && $document->completed_timestamp) {
                $receivedTimestamp = new \DateTime($document->received_timestamp);
                $completedTimestamp = new \DateTime($document->completed_timestamp);

                $interval = $receivedTimestamp->diff($completedTimestamp);
                $processingTimeMinutes = $interval->days * 24 * 60 + $interval->h * 60 + $interval->i;

                $officeId = $document->current_office_id;

                if (!isset($processingTimesByOffice[$officeId])) {
                    $processingTimesByOffice[$officeId] = [];
                    $documentCountsByOffice[$officeId] = 0;
                }

                $processingTimesByOffice[$officeId][] = $processingTimeMinutes;
                $documentCountsByOffice[$officeId]++;
            }
        }

        $averageProcessingTimes = [];

        foreach ($processingTimesByOffice as $officeId => $times) {
            $totalTime = array_sum($times);
            $count = $documentCountsByOffice[$officeId];
            $averageTime = $totalTime / $count; 
            $averageProcessingTimes[$officeId] = round($averageTime, 2); 
        }

        $data['averageProcessingTimes'] = $averageProcessingTimes;
        $data['officeNames'] = array_keys($averageProcessingTimes); 
        $data['averageProcessingTimes'] = array_values($averageProcessingTimes);
        $data['statusLabels'] = json_encode($statusLabels);
        $data['statusCounts'] = json_encode($statusCounts);
        $data['officeLabels'] = json_encode($officeLabels);
        $data['officeCounts'] = json_encode($officeCounts);
        $data['userLabels'] = json_encode($userLabels);
        $data['totalStatuses'] = $totalStatuses;
        $data['userCounts'] = json_encode($userCounts);
        $data['totalDocuments'] = $totalDocuments;
        $data['totalUsers'] = array_sum($userCounts);
        $data['documentLabels'] = json_encode(array_values($documentLabels));
        $data['documentAgesDays'] = json_encode(array_values($documentAgesDays));
        $data['documentAgesMonths'] = json_encode(array_values($documentAgesMonths));
        $data['totalOffices'] = $totalOffices;

        return view('Admin/AdminDashboard', $data);
    }

    public function adminmanageoffice()
    {
        $officeModel = new OfficeModel();
        $data['offices'] = $officeModel->where('status', 'active')->findAll();

        return view('Admin/AdminManageOffice', $data);
    }
    public function login()
    {
        $userModel = new UserModel();
        $jwtService = new JWTServices(); 
    
        if ($this->request->getMethod() === 'post') {
            $emailOrUsername = $this->request->getPost('emailOrUsername');
            $password = $this->request->getPost('password');
    
            $user = $userModel->where('email', $emailOrUsername)
                              ->orWhere('username', $emailOrUsername)
                              ->first();
    
            if ($user) {
                if ($user['status'] === 'deactivate') {
                    log_message('error', 'Login attempt failed. Account is deactivated. Email/Username: ' . $emailOrUsername);
                    session()->setFlashdata('error', 'Login attempt failed. Your account is deactivated as of the moment.');
                    return redirect()->back();
                }
    
                if (password_verify($password, $user['password'])) {
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
    
                    $token = $jwtService->generateToken($userData);
    
                    session()->set('jwt_token', $token);
                    log_message('info', 'User logged in successfully. JWT Token: ' . $token);
    
                    session()->set($userData);
    
                    switch ($user['role']) {
                        case 'admin':
                            return redirect()->to('dashboard');
                        case 'office user':
                            return redirect()->to('index');
                        case 'guest':
                        default:
                            return redirect()->to('adminkiosk');
                    }
                } else {
                    // Log failed login attempt
                    log_message('error', 'Login attempt failed. Invalid password. Email/Username: ' . $emailOrUsername);
                    session()->setFlashdata('error', 'Invalid email or password.');
                }
            } else {
                // Log failed login attempt
                log_message('error', 'Login attempt failed. Email/Username: ' . $emailOrUsername);
                session()->setFlashdata('error', 'Invalid email or password.');
            }
        }
    
        return view('LogIn');
    }
    
public function register()
{
    helper(['form']);

    if ($this->request->getMethod() === 'post') {

        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|valid_email',
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

            $email = $this->request->getVar('email');

            // Check if a user with role 'guest' and the same email already exists
            $userModel = new UserModel();
            $existingUser = $userModel->where('email', $email)
                                      ->where('role', 'guest')
                                      ->first();

            if ($existingUser) {
                // Add error message to the validation array
                $data['validation'] = $this->validator;
                $data['validation']->setError('email', 'Account already exists.');
            } else {
                $imagePath = '';

                $userData = [
                    'first_name' => $this->request->getVar('first_name'),
                    'last_name' => $this->request->getVar('last_name'),
                    'email' => $email,
                    'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
                    'picture_path' => $imagePath,
                    'role' => 'guest',
                    'office_id' => null,
                    'username' => $email,
                ];

                $userModel->insert($userData);

                return redirect()->to('/');
            }
        }

        return view('Register', $data);
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
        $data['guestUsers'] = $userModel->select('user_id, first_name, last_name, email, picture_path, status')
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
    
        if (!preg_match('/[A-Z]/', $password) || 
            !preg_match('/[a-z]/', $password) || 
            !preg_match('/[0-9]/', $password) ||
            strlen($password) < 8) {
            return $this->response->setJSON(['error' => 'Password must contain at least 8 characters, including uppercase, lowercase, and numbers.']);
        }
    
        $existingUser = $userModel->where('email', $email)
                                  ->where('role', 'guest')
                                  ->first();
    
        if ($existingUser) {
            return $this->response->setJSON(['error' => 'Account already exists. Please add a new one.']);
        } else {
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
        
            return $this->response->setJSON(['success' => 'Guest user added successfully.']);
        }
    }
    
    public function manageuser()
    {
        $userModel = new UserModel();
        $users = $userModel->select('users.*, offices.office_name')
            ->join('offices', 'offices.office_id = users.office_id', 'left')
            ->whereIn('users.role', ['admin', 'office user']) 
            ->findAll();
    
        $officeModel = new OfficeModel();
        $data['offices'] = $officeModel->findAll();
        $data['users'] = $users;
    
        return view('Admin/AdminManageUser', $data);
    }
    
    public function save()
    {
        $model = new OfficeModel();
    
        $validation = \Config\Services::validation();
        $validation->setRules([
            'officeName' => 'required',
        ]);
    
        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $validation->getErrors(),
            ]);
        }
    
        $officeName = $this->request->getPost('officeName');
        $existingOffice = $model->where('office_name', $officeName)->first();
    
        if ($existingOffice) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Department already existed. Please add a new one.',
            ]);
        }
    
        $model->save([
            'office_name' => $officeName,
        ]);
    
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Department added successfully.',
        ]);
    }
    
    

    public function saveOfficeUser()
{
    $userModel = new UserModel();
    $officeModel = new OfficeModel();
    

    $firstName = $this->request->getPost('firstName');
    $lastName = $this->request->getPost('lastName');
    $username = $this->request->getPost('username');
    $password = $this->request->getPost('password');
    $officeId = $this->request->getPost('officeId');


    if (!preg_match('/[A-Z]/', $password) || 
        !preg_match('/[a-z]/', $password) || 
        !preg_match('/[0-9]/', $password) ||
        strlen($password) < 8) {
        return $this->response->setJSON(['error' => 'Password must contain at least 8 characters, including uppercase, lowercase, and numbers.']);
    }


    $office = $officeModel->find($officeId);
    if (!$office) {
        return $this->response->setJSON(['error' => 'Office not found.']);
    }


    $existingUser = $userModel->where('username', $username)
                              ->orWhere('email', $username)
                              ->first();
    if ($existingUser) {
        return $this->response->setJSON(['error' => 'Account already exists. Please add a new username and password.']);
    }


    $userData = [
        'first_name' => $firstName,
        'last_name' => $lastName,
        'username' => $username,
        'email' => $username, // Assuming username is also the email
        'password' => password_hash($password, PASSWORD_DEFAULT),
        'office_id' => $officeId,
        'image' => '',
        'role' => 'office user',
    ];


    $userModel->insert($userData);

    return $this->response->setJSON(['success' => 'Office user added successfully.']);
}

    
    
    public function managedocument()
    {
        $userModel = new UserModel();
        $guestUsers = $userModel->where('role', 'guest')->findAll();
    
        $documentModel = new DocumentModel();
        $documents = $documentModel
        ->select('documents.document_id, documents.version_number, documents.title, documents.tracking_number, documents.sender_id, documents.recipient_id, documents.status, documents.date_of_document, documents.action, documents.description, documents.attachment, sender.first_name AS sender_first_name, sender.last_name AS sender_last_name, recipient.office_name AS recipient_office_name, c.classification_name AS classification, c.sub_classification AS sub_classification')
        ->join('(SELECT document_id, MAX(version_number) AS max_version FROM documents GROUP BY document_id) latest', 'documents.document_id = latest.document_id AND documents.version_number = latest.max_version', 'inner')
        ->join('classification c', 'c.classification_id = documents.classification_id', 'left')
        ->join('users sender', 'sender.user_id = documents.sender_id', 'left')
        ->join('offices recipient', 'recipient.office_id = documents.recipient_id', 'left')
        ->where('documents.status !=', 'deleted')
        ->where('sender.role', 'guest')
        ->whereIn('documents.title', function($builder) {
            $builder->select('title')
                ->from('documents')
                ->groupBy('title');
        })
        ->orderBy('documents.date_of_document', 'DESC')
        ->findAll();
        
        $classificationModel = new ClassificationModel();
        $classifications = $classificationModel
            ->distinct()
            ->select('classification_name')
            ->where('status', 'active')
            ->findColumn('classification_name');
        
        $classificationsDropdown = is_array($classifications) ? array_values($classifications) : [];
        
        $subClassifications = $classificationModel
            ->where('status', 'active')
            ->findAll();
        
        $subClassificationsDropdown = array_column($subClassifications, 'sub_classification');
        

        $officeModel = new OfficeModel();
        $offices = $officeModel->where('status', 'active')->findAll();
        $officesDropdown = [];
        foreach ($offices as $office) {
            $officesDropdown[$office['office_id']] = $office['office_name'];
        }
    
        $guestUsersNames = [];
        foreach ($guestUsers as $user) {
            if ($user['status'] === 'activate') {
                $guestUsersNames[$user['user_id']] = $user['first_name'] . ' ' . $user['last_name'];
            }
        }
        
        $data = [
            'documents' => $documents,
            'classificationsDropdown' => $classificationsDropdown,
            'subClassificationsDropdown' => $subClassificationsDropdown,
            'officesDropdown' => $officesDropdown,
            'guestUsersNames' => $guestUsersNames, 
        ];
    
        return view('Admin/AdminManageDocument', $data);
    }

    public function manageofficedocument()
    {
        $documentModel = new DocumentModel();
        $documents = $documentModel
        ->select('documents.document_id, documents.version_number, documents.title, documents.tracking_number, documents.sender_office_id, documents.recipient_id, documents.status, documents.date_of_document, documents.action, documents.description, documents.attachment, sender.office_name AS sender_office_name, recipient.office_name AS recipient_office_name, c.classification_name AS classification, c.sub_classification AS sub_classification')
        ->join('(SELECT document_id, MAX(version_number) AS max_version FROM documents GROUP BY document_id) latest', 'documents.document_id = latest.document_id AND documents.version_number = latest.max_version', 'inner')
        ->join('classification c', 'c.classification_id = documents.classification_id', 'left')
        ->join('offices sender', 'sender.office_id = documents.sender_office_id', 'left')
        ->join('offices recipient', 'recipient.office_id = documents.recipient_id', 'left')
        ->where('documents.status !=', 'deleted')
        ->where('documents.sender_office_id IS NOT NULL')
        ->whereIn('(documents.title, documents.version_number)', function($builder) {
            return $builder->select('title, MAX(version_number)')
                ->from('documents')
                ->groupBy('title');
        })
        ->orderBy('documents.date_of_document', 'DESC')
        ->findAll();

        $classificationModel = new ClassificationModel();
        $classifications = $classificationModel
            ->distinct()
            ->select('classification_name')
            ->where('status', 'active')
            ->findColumn('classification_name');
        
        $classificationsDropdown = is_array($classifications) ? array_values($classifications) : [];
        
        $subClassifications = $classificationModel
            ->where('status', 'active')
            ->findAll();
        
        $subClassificationsDropdown = array_column($subClassifications, 'sub_classification');
        
        

        $officeModel = new OfficeModel();
        $offices = $officeModel->where('status', 'active')->findAll();
        $officesDropdown = [];
        foreach ($offices as $office) {
            $officesDropdown[$office['office_id']] = $office['office_name'];
        }
        

        $data = [
            'documents' => $documents,
            'classificationsDropdown' => $classificationsDropdown,
            'subClassificationsDropdown' => $subClassificationsDropdown,
            'officesDropdown' => $officesDropdown,
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

        $classifications = $classificationModel->where('status', 'active')->findAll();

        $distinctClassifications = $classificationModel
        ->where('status', 'active')
        ->distinct()
        ->findColumn('classification_name') ?? [];
    

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
        $builder = $db->table('documents');
    
        $classification = $this->request->getVar('classification');
        $subClassification = $this->request->getVar('sub_classification');
        $title = $this->request->getVar('title');
    
        try {
            $db->transBegin();
            $latestDocument = $builder->where('title', $title)->orderBy('version_number', 'DESC')->get()->getRowArray();
    
            if ($latestDocument) {
                $parent_id = $latestDocument['document_id'];
                $versionParts = explode('.', $latestDocument['version_number']);
                if (count($versionParts) == 2) {
                    $major = intval($versionParts[0]);
                    $major++;
                    $version_number = $major . '.0';
                } else {
                    $version_number = '2.0';
                }
            } else {
                $parent_id = NULL;
                $version_number = '1.0';
            }
    
            $trackingNumber = 'TR-' . uniqid();
            $data = [
                'tracking_number' => $trackingNumber,
                'sender_id' => $this->request->getVar('sender_office_id'),
                'sender_office_id' => NULL,
                'recipient_id' => $this->request->getVar('recipient_office_id'),
                'status' => 'pending',
                'title' => $title,
                'description' => $this->request->getVar('description'),
                'action' => $this->request->getVar('action'),
                'date_of_document' => date('Y-m-d'),
                'attachment' => $newName,
                'classification_id' => NULL,
                'classification' => $classification,
                'sub_classification' => $subClassification,
                'date_completed' => NULL,
                'version_number' => $version_number,
                'parent_id' => $parent_id
            ];
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
    
        $db = \Config\Database::connect();
        $builder = $db->table('documents');
    
        $title = $this->request->getPost('title');
        $senderOfficeId = $this->request->getPost('sender_office_id');
        $recipientOfficeId = $this->request->getPost('recipient_office_id');
    
        $existingDocument = $builder
            ->where('title', $title)
            ->where('sender_office_id', $senderOfficeId)
            ->where('recipient_id', $recipientOfficeId)
            ->orderBy('version_number', 'DESC')
            ->get()
            ->getRowArray();
    
        if ($existingDocument) {
            $parent_id = $existingDocument['document_id'];
            $versionParts = explode('.', $existingDocument['version_number']);
            if (count($versionParts) == 2) {
                $major = intval($versionParts[0]);
                $major++;
                $version_number = $major . '.0';
            } else {
                $version_number = '2.0';
            }
        } else {
            $parent_id = NULL;
            $version_number = '1.0';
        }
    
        $data = [
            'tracking_number' => $trackingNumber,
            'sender_id' => NULL,
            'title' => $title,
            'sender_office_id' => $senderOfficeId,
            'recipient_id' => $recipientOfficeId,
            'status' => 'pending',
            'classification' => $this->request->getPost('classification'),
            'sub_classification' => $this->request->getPost('sub_classification'),
            'date_of_document' => date('Y-m-d H:i:s', strtotime($this->request->getPost('date_of_document'))),
            'attachment' => $attachmentName,
            'action' => $this->request->getPost('action'),
            'description' => $this->request->getPost('description'),
            'classification_id' => NULL,
            'date_completed' => NULL,
            'version_number' => $version_number,
            'parent_id' => $parent_id
        ];
    
        $builder->insert($data);
    
        return redirect()->to(base_url('officetracking?trackingNumber=' . $trackingNumber));
    }
    

    public function admintransactions()
    {
        // Get the filter values from the GET request
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');
        $statusFilter = $this->request->getGet('status'); // e.g., 'received'
        $departmentFilter = $this->request->getGet('department'); // e.g., 'Mayor\'s Office'
    
        $db = db_connect();
    
        // Query to get distinct statuses from the documents table
        $statusQuery = $db->query("SELECT DISTINCT status FROM documents");
        $statuses = $statusQuery->getResultArray(); // Fetch statuses as an array
    
        // Build the base query for fetching documents
        $query = "
            SELECT 
                d.document_id,
                d.tracking_number, 
                d.title, 
                d.sender_id, 
                d.sender_office_id,
                d.recipient_id,
                d.status, 
                dh.user_id,
                dh.office_id AS current_office_id,
                dh.status AS history_status,
                dh.date_changed,
                dh.date_completed,
                o1.office_name AS recipient_office_name,
                u.first_name AS sender_first_name,
                u.last_name AS sender_last_name,
                tp.received_timestamp,
                tp.completed_timestamp,
                o2.office_name AS completed_office_name
            FROM documents d
            JOIN document_history dh ON d.document_id = dh.document_id
            LEFT JOIN offices o1 ON d.recipient_id = o1.office_id
            LEFT JOIN document_timeprocessing tp ON d.document_id = tp.document_id AND dh.office_id = tp.office_id
            LEFT JOIN users u ON d.sender_id = u.user_id
            LEFT JOIN users u2 ON dh.user_id = u2.user_id
            LEFT JOIN offices o2 ON u2.office_id = o2.office_id
            WHERE dh.status = 'completed'"; // Default filter
    
        // Apply filters dynamically based on user input
        if ($startDate && $endDate) {
            $query .= " AND dh.date_completed BETWEEN '$startDate' AND '$endDate'";
        }
    
        if ($statusFilter) {
            $query .= " AND d.status = '$statusFilter'";
        }
    
        if ($departmentFilter) {
            $query .= " AND o1.office_name = '$departmentFilter'";
        }
    
        // Execute the query to fetch documents
        $queryResult = $db->query($query);
        
        if (!$queryResult) {
            return 'Error: Unable to fetch completed documents';
        }
    
        $documents = $queryResult->getResult();
        $senderDetails = [];
    
        foreach ($documents as $document) {
            $receivedTimestamp = new \DateTime($document->received_timestamp);
            $completedTimestamp = new \DateTime($document->completed_timestamp);
    
            $interval = $receivedTimestamp->diff($completedTimestamp);
            $processingTimeMinutes = $interval->days * 24 * 60 + $interval->h * 60 + $interval->i;
    
            $document->processing_time_minutes = $processingTimeMinutes;
    
            $formattedTime = '';
    
            if ($processingTimeMinutes >= 1440) { 
                $days = floor($processingTimeMinutes / 1440);
                $formattedTime = $days . ' day' . ($days > 1 ? 's' : '');
            } elseif ($processingTimeMinutes >= 60) { 
                $hours = floor($processingTimeMinutes / 60);
                $formattedTime = $hours . ' hr' . ($hours > 1 ? 's' : '');
            } else {
                $formattedTime = $processingTimeMinutes . ' min';
            }
    
            $document->formatted_time = $formattedTime;
    
            $sender_id = $document->sender_id;
            $sender_office_id = $document->sender_office_id;
    
            if ($sender_office_id === null) {
                $sender_name = $document->sender_first_name . ' ' . $document->sender_last_name;
                $sender_office = 'N/A'; 
            } else {
                $officeModel = new OfficeModel();
                $office = $officeModel->find($sender_office_id);
                $sender_name = 'N/A';
                $sender_office = $office['office_name'];
            }
    
            $senderDetails[$document->document_id] = [
                'sender_user' => $sender_name,
                'sender_office' => $sender_office,
            ];
        }
    
        // Fetch the department list for the dropdown
        $officeModel = new OfficeModel();
        $departments = $officeModel->findAll();
    
        $data = [
            'documents' => $documents,
            'senderDetails' => $senderDetails,
            'departments' => $departments, // Pass the department list to the view
            'statuses' => $statuses, // Pass statuses to the view
        ];
    
        return view('Admin/AdminViewTransactions', $data);
    }
    
    public function archived()
    {
        $documentModel = new DocumentModel();

        $documents = $documentModel
            ->select('documents.document_id, documents.tracking_number, documents.title, NULL AS deleted_by, NULL AS date_deleted')
            ->where('documents.status', 'deleted')
            ->findAll();

        $documents = array_map(function ($item) {
            return (object) $item;
        }, $documents);

        $data = [
            'documents' => $documents
        ];

        return view('Admin/AdminArchived', $data);
    }


    public function documentStatusChart()
    {
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

    public function deleteDocument()
    {
        $documentModel = new DocumentModel();
    
        if ($this->request->isAJAX()) {
            $documentId = $this->request->getPost('documentId');

            $data = [
                'status' => 'deleted',  
            ];
    
            $result = $documentModel->update($documentId, $data);
    
            if ($result) {
                return $this->response->setJSON(['success' => true]);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to update document status.']);
            }
        }
    }
    

    public function updateDocumentDeletedStatus($documentId, $newStatus)
    {
        try {
            $documentModel = new DocumentModel();
            $workflowModel = new DocumentHistoryModel();

            $documentModel->update($documentId, ['status' => $newStatus]);

            $data = [
                'document_id' => $documentId,
                'user_id' => null,
                'office_id' => null,
                'status' => $newStatus,
                'date_changed' => date('Y-m-d H:i:s'),
                'date_deleted' => $newStatus === 'deleted' ? date('Y-m-d H:i:s') : null
            ];
            $workflowModel->insert($data);

            return redirect()->back();
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            exit;
        }
    }

    public function deleteDocumentPermanent($documentId)
    {
        $documentHistoryModel = new DocumentHistoryModel();
        $documentHistoryModel->where('document_id', $documentId)->delete();
        return redirect()->to('Admin/AdminArchived');
    }

    public function updateUser()
    {
        $userId = $this->request->getPost('userId');
        $userModel = new UserModel();
        
        $userData = [
            'office_id' => $this->request->getPost('officeId'),
            'username' => $this->request->getPost('username'),
        ];
    
        $newPassword = $this->request->getPost('password');
        if (!empty($newPassword)) {
            $userData['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }
    
        $file = $this->request->getFile('profilePicture');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $uploadPath = 'public/uploads/';
            $newFileName = time() . '_' . $file->getName();
            $file->move($uploadPath, $newFileName);
    
            $userData['picture_path'] = $uploadPath . $newFileName;
        }
    
        $userModel->update($userId, $userData);
        
        return redirect()->to('manageuser');
    }
    

    public function delete($userId)
    {
        $userModel = new UserModel();
        $userModel->delete($userId);
        return redirect()->to('manageuser');
    }
    

    public function updateGuestUser()
    {
        $userId = $this->request->getPost('userId');
        $userModel = new UserModel();
    
        $userData = [
            'first_name' => $this->request->getPost('firstName'),
            'last_name' => $this->request->getPost('lastName'),
            'email' => $this->request->getPost('email'),
        ];
    
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $userData['password'] = password_hash($password, PASSWORD_DEFAULT);
        }
    
        $userModel->update($userId, $userData);
    
        return redirect()->to('manageguest');
    }
    
    public function updateDocument()
    {
        $documentId = $this->request->getPost('id');
        $documentModel = new DocumentModel();
    
        $documentData = [
            'title' => $this->request->getPost('title'),
            'sender_office_id' => $this->request->getPost('sender_office_id'),
            'recipient_id' => $this->request->getPost('recipient_office_id'),
            'action' => $this->request->getPost('action'),
            'description' => $this->request->getPost('description'),
        ];
    
        if ($this->request->getFile('attachment')->isValid()) {
            $attachment = $this->request->getFile('attachment');
            $attachment->move(WRITEPATH . 'uploads');
            $documentData['attachment'] = $attachment->getName();
        }
    
        if (!empty($documentData)) {
            $result = $documentModel->set($documentData)->where('document_id', $documentId)->update();
            if ($result) {
                echo '<script>window.location.reload();</script>';
                exit();
            }
        }
    
        return redirect()->to('manageofficedocument')->with('success', 'Document updated successfully.');
    }
    

    public function updateGuestDocument()
    {
        $documentId = $this->request->getPost('document_id');
        $documentModel = new DocumentModel();
    
        $documentData = [
            'title' => $this->request->getPost('title'),
            'sender_id' => $this->request->getPost('sender_id'),
            'recipient_id' => $this->request->getPost('recipient_office_id'),
            'action' => $this->request->getPost('action'),
            'description' => $this->request->getPost('description'),
        ];
    
        if ($this->request->getFile('attachment')->isValid()) {
            $attachment = $this->request->getFile('attachment');
            $attachment->move(WRITEPATH . 'uploads');
            $documentData['attachment'] = $attachment->getName();
        }
    
        if (!empty($documentData)) {
            $result = $documentModel->set($documentData)->where('document_id', $documentId)->update();
            if ($result) {
                echo '<script>window.location.reload();</script>';
                exit();
            }
        }
    
        return redirect()->to('managedocument')->with('success', 'Document updated successfully.');
    }
    
    
    

    public function getDocument()
    {
        $documentId = $this->request->getPost('id');
        $documentModel = new DocumentModel();
        $document = $documentModel->find($documentId);

        return $this->response->setJSON($document);
    }

    public function alldocuments()
    {
        $documentModel = new DocumentModel();
    
        $searchResults = $documentModel
            ->select('documents.*, sender.office_name AS sender_office_name, recipient.office_name AS recipient_office_name, c.classification_name AS classification, c.sub_classification AS sub_classification')
            ->join('offices sender', 'sender.office_id = documents.sender_office_id', 'left')
            ->join('offices recipient', 'recipient.office_id = documents.recipient_id', 'left')
            ->join('classification c', 'c.classification_id = documents.classification_id', 'left')
            ->whereIn('(documents.title, documents.version_number)', function($builder) {
                return $builder->select('title, MAX(version_number)')
                    ->from('documents')
                    ->groupBy('title');
            })
            ->findAll();
    
        $officeModel = new OfficeModel();
        $offices = $officeModel->findAll();
    
        $data = [
            'searchResults' => $searchResults,
            'offices' => $offices
        ];
    
        return view('Admin/AdminAllDocuments', $data);
    }

    public function kiosk()
    {
        $documentModel = new DocumentModel();
    
        $searchResults = $documentModel
            ->select('documents.*, sender.office_name AS sender_office_name, recipient.office_name AS recipient_office_name, c.classification_name AS classification, c.sub_classification AS sub_classification')
            ->join('offices sender', 'sender.office_id = documents.sender_office_id', 'left')
            ->join('offices recipient', 'recipient.office_id = documents.recipient_id', 'left')
            ->join('classification c', 'c.classification_id = documents.classification_id', 'left')
            ->whereIn('(documents.title, documents.version_number)', function($builder) {
                return $builder->select('title, MAX(version_number)')
                    ->from('documents')
                    ->groupBy('title');
            })
            ->findAll();
    
        $officeModel = new OfficeModel();
        $offices = $officeModel->findAll();
    
        $data = [
            'searchResults' => $searchResults,
            'offices' => $offices
        ];
    
        return view('Admin/AdminKiosk', $data);
    }
    
    public function search()
    {
        $searchQuery = $this->request->getVar('search');
        $officeFilter = $this->request->getVar('office');
        $statusFilter = $this->request->getVar('status');
        $sortOption = $this->request->getVar('sort');
        
        $db = \Config\Database::connect();
        $query = $db->table('documents');
        
        if (!empty($searchQuery)) {
            $query->groupStart()
                  ->like('title', $searchQuery)
                  ->orLike('tracking_number', $searchQuery)
                  ->groupEnd();
        }
        
        if (!empty($officeFilter)) {
            $query->where('recipient_id', $officeFilter);
        }
        if (!empty($statusFilter)) {
            $query->where('status', $statusFilter);
        }
    
        if ($sortOption === 'title_asc') {
            $query->orderBy('title', 'ASC');
        } elseif ($sortOption === 'title_desc') {
            $query->orderBy('title', 'DESC');
        } elseif ($sortOption === 'date_asc') {
            $query->orderBy('created_at', 'ASC');
        } elseif ($sortOption === 'date_desc') {
            $query->orderBy('created_at', 'DESC');
        }
    
        $searchResults = $query->get()->getResultArray();
    
        $officeModel = new \App\Models\OfficeModel();
        $offices = $officeModel->findAll();
    
        $data = [
            'searchResults' => $searchResults,
            'offices' => $offices
        ];
        
        return view('Admin/AdminAllDocuments', $data);
    }

    public function searchkiosk()
    {
        $searchQuery = $this->request->getVar('search');
        $officeFilter = $this->request->getVar('office');
        $statusFilter = $this->request->getVar('status');
        $sortOption = $this->request->getVar('sort');
        
        
        $db = \Config\Database::connect();
        $query = $db->table('documents');
        
        if (!empty($searchQuery)) {
            $query->groupStart()
                  ->like('title', $searchQuery)
                  ->orLike('tracking_number', $searchQuery)
                  ->groupEnd();
        }
        
        if (!empty($officeFilter)) {
            $query->where('recipient_id', $officeFilter);
        }
        if (!empty($statusFilter)) {
            $query->where('status', $statusFilter);
        }
    
        if ($sortOption === 'title_asc') {
            $query->orderBy('title', 'ASC');
        } elseif ($sortOption === 'title_desc') {
            $query->orderBy('title', 'DESC');
        } elseif ($sortOption === 'date_asc') {
            $query->orderBy('created_at', 'ASC');
        } elseif ($sortOption === 'date_desc') {
            $query->orderBy('created_at', 'DESC');
        }
    
        $searchResults = $query->get()->getResultArray();
    
        $officeModel = new \App\Models\OfficeModel();
        $offices = $officeModel->findAll();
    
        $data = [
            'searchResults' => $searchResults,
            'offices' => $offices
        ];
        
        return view('Admin/AdminKiosk', $data);
    }

    public function download_all_rows()
    {
        $db = db_connect();

        // Get the start date, end date, and department from POST data
        $startDate = $this->request->getPost('start_date');
        $endDate = $this->request->getPost('end_date');
        $department = $this->request->getPost('department');  // Get the department ID from the form

        // Start building the query
        $query = $db->table('documents')
            ->select('
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
                offices.office_name as sender_office_name,
                users.first_name as sender_first_name,
                users.last_name as sender_last_name,
                tp.received_timestamp,
                tp.completed_timestamp,
                TIMESTAMPDIFF(MINUTE, tp.received_timestamp, tp.completed_timestamp) AS processing_time_minutes  -- Calculating processing time
            ')
            ->join('document_history', 'documents.document_id = document_history.document_id')
            ->join('offices', 'documents.sender_office_id = offices.office_id', 'left')
            ->join('document_timeprocessing tp', 'documents.document_id = tp.document_id AND document_history.office_id = tp.office_id', 'left')
            ->join('users', 'documents.sender_id = users.user_id', 'left')
            ->where('document_history.status', 'completed');  // Filter by completed status

        if (!empty($startDate) && !empty($endDate)) {
            $query->where('document_history.date_completed >=', $startDate)
                ->where('document_history.date_completed <=', $endDate);
        }

        // Add department filter if a department is selected
        if (!empty($department)) {
            $query->where('documents.sender_office_id', $department);  // Filter by sender's office ID
        }

        // Execute the query
        $documents = $query->get()->getResultArray();

        $response = [];
        foreach ($documents as $document) {
            // Get recipient office name
            $recipient_office_name = '';
            if ($document['recipient_id'] !== null) {
                $officeModel = new \App\Models\OfficeModel();
                $office = $officeModel->find($document['recipient_id']);
                if ($office) {
                    $recipient_office_name = $office['office_name'];
                }
            }

            // Prepare response data
            $response[] = [
                'tracking_number' => $document['tracking_number'],
                'title' => $document['title'],
                'sender' => $document['sender_office_name'] ? $document['sender_office_name'] : $document['sender_first_name'] . ' ' . $document['sender_last_name'],
                'current_office' => $recipient_office_name,
                'processing_time' => $document['processing_time_minutes'],  // Use pre-computed processing time
                'date_completed' => date('F j, Y', strtotime($document['date_completed']))
            ];
        }

        // If the request is AJAX, return JSON response
        if ($this->request->isAJAX()) {
            return $this->response->setJSON($response);
        }

        // Prepare CSV data
        $csvData = "Tracking Number,Title,Sender,Current Office,Processing Time (minutes),Date Completed\n";
        foreach ($response as $document) {
            $csvData .= $document['tracking_number'] . ',' . $document['title'] . ',' ;
            $csvData .= strip_tags($document['sender']) . ',' ;
            $csvData .= strip_tags($document['current_office']) . ',' ;
            $csvData .= strip_tags($document['processing_time']) . ',' ;
            $csvData .= $document['date_completed'] . "\n";
        }

        // Output the CSV file
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="document_transactions.csv"');
        echo $csvData;
        exit();
    }
    
    public function getDocumentAges()
    {
        $documentModel = new DocumentModel();
        $documents = $documentModel->findAll();
        $documentAges = [];
        foreach ($documents as $document) {
            $dateOfDocument = new \DateTime($document->date_of_document);
            $currentDate = new \DateTime();
            $diff = $currentDate->diff($dateOfDocument);
            $ageInDays = $diff->days;
            $documentAges[] = $ageInDays;
        }
        return $this->response->setJSON($documentAges);
    }
    
public function getAverageProcessingTimes()
{
    $db = \Config\Database::connect();
    $query = $db->query('
        SELECT
            office_id,
            AVG(TIMESTAMPDIFF(SECOND, received_timestamp, completed_timestamp)) AS avg_processing_time_seconds
        FROM
            document_timeprocessing
        GROUP BY
            office_id
    ');

    $averageProcessingTimes = $query->getResultArray();
    return $this->response->setJSON($averageProcessingTimes);
} 

public function aging() {
    $builder = $this->db->table('documents');
    $builder->select('title, date_of_document');
    $query = $builder->get();

    $documents = $query->getResultArray();
    $current_date = new DateTime();

    foreach ($documents as &$doc) {
        $doc_date = new DateTime($doc['date_of_document']);
        $doc['age'] = $current_date->diff($doc_date)->days;
    }

    return $this->response->setJSON($documents);
}

public function updateOfficeName()
{
    $officeId = $this->request->getPost('officeId');
    $newOfficeName = $this->request->getPost('officeName');

    $model = new OfficeModel();
    $model->update($officeId, ['office_name' => $newOfficeName]);

    return redirect()->to('manageoffice');
}

public function updateStatus()
{
    $officeModel = new OfficeModel();
    
    $office_id = $this->request->getPost('officeId');
    $officeModel->update($office_id, ['status' => 'deleted']);
    return redirect()->to(base_url('manageoffice'));
}

public function updateClassification()
{
    $classificationModel = new ClassificationModel();
    
    $classification_id = $this->request->getPost('officeId');
    $classificationModel->update($classification_id, ['status' => 'deleted']);
    
    return redirect()->to(base_url('maintenance'));
}
public function updateClassificationName()
{
    $classificationId = $this->request->getPost('classificationId');
    $newClassificationName = $this->request->getPost('classification');
    $newSubClassificationName = $this->request->getPost('subclassificationName');

    $classificationModel = new ClassificationModel();
    $classificationModel->where('classification_id', $classificationId)
                         ->set(['classification_name' => $newClassificationName, 'sub_classification' => $newSubClassificationName])
                         ->update();

    return redirect()->to('maintenance'); 
}


public function fetchVersionsByTitle()
{
    $title = $this->request->getGet('title');

    $db      = \Config\Database::connect();
    $builder = $db->table('documents');

    $documents = $builder
        ->select('document_id, tracking_number, version_number, title')
        ->where('title', $title)
        ->get()
        ->getResultArray();

    return json_encode($documents); // Return the documents as JSON
}

    public function logout()
    {
        session()->remove('jwt_token');

        session()->destroy();

        return redirect()->to('/'); // Adjust the redirect as needed
    }

    public function adminindex()
    {
        return view('Admin/AdminIndex');
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

        return view('Admin/AdminSearchResult', ['document' => $document, 'office' => $office, 'progressPercentage' => $progressPercentage]);
    }

    public function viewdetails()
    {
        $trackingNumber = $this->request->getVar('tracking_number');
    
        $documentModel = new DocumentModel();
        $document = $documentModel->where('tracking_number', $trackingNumber)->first();
    
        if (!$document) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Document not found");
        }
    
        $workflowModel = new DocumentHistoryModel();
        $workflow_history = $workflowModel->where('document_id', $document['document_id'])->findAll();

        $adminModel = new UserModel();
        $admins = $adminModel->findAll();
    
        $officeModel = new OfficeModel();
        $offices = $officeModel->findAll();
    
        $userMap = [];
        foreach ($admins as $admin) {
            $userMap[$admin['user_id']] = $admin;
        }
    
        $officeMap = [];
        foreach ($offices as $office) {
            $officeMap[$office['office_id']] = $office;
        }
    
        $recipientOffice = $officeModel->find($document['recipient_id']);
    
        $data = [
            'tracking_number' => $trackingNumber,
            'workflow_history' => $workflow_history,
            'title' => $document['title'],
            'admins' => $userMap, // Use the mapped user data
            'offices' => $officeMap, // Use the mapped office data
            'recipient_office' => $recipientOffice ? $recipientOffice['office_name'] : 'Unknown Office',
        ];
    
        return view('Admin/AdminViewDetails', $data);
    }

    public function activateUser()
    {
        $userId = $this->request->getPost('user_id');
        $model = new UserModel();

        $model->update($userId, ['status' => 'activate']);

        return $this->response->setJSON(['status' => 'success']);
    }

    public function deactivateUser()
    {
        $userId = $this->request->getPost('user_id');
        $model = new UserModel();

        $model->update($userId, ['status' => 'deactivate']);

        return $this->response->setJSON(['status' => 'success']);
    }

    public function deleteUser($id)
    {
        $userModel = new UserModel();
        $user = $userModel->find($id);
        
        if ($user) {
            $userModel->delete($id);
            return redirect()->to('/manageguest')->with('success', 'User deleted successfully');
        } else {
            return redirect()->to('/manageguest')->with('error', 'User not found');
        }
    }

    public function manageUsers()
    {
        $this->userModel = new UserModel();
        $this->officeModel = new OfficeModel(); 
    
        $searchTerm = $this->request->getVar('search');
        $officeId = $this->request->getVar('office');
    
        // Prepare the query
        $query = $this->userModel->select('users.*, offices.office_name')
                                 ->join('offices', 'users.office_id = offices.office_id', 'left')
                                 ->where('users.role', 'office user')
                                 ->orderBy('users.user_id', 'DESC'); // Sorting by user_id in descending order
    
        if (!empty($searchTerm)) {
            $query->groupStart() // Group conditions for OR logic
                  ->like('users.username', $searchTerm)
                  ->orLike('users.email', $searchTerm)
                  ->groupEnd();
        }
    
        if (!empty($officeId)) {
            $query->where('users.office_id', $officeId);
        }
    
        $data['users'] = $query->get()->getResult(); // Execute the query and fetch the results
    
        // Fetch all offices for the dropdown
        $data['offices'] = $this->officeModel->select('office_id, office_name')->distinct()->findAll();
    
        return view('Admin/AdminManageUser', $data);
    }
    
    

    public function getOfficeList(){
        $officeModel = new OfficeModel();
        $offices = $officeModel->select([
            'office_id',
            'office_name'
        ])->where('status', 'active')->findAll();

        return $this->response->setJSON($offices);
    }

    public function getGuestList() {
        $userModel = new UserModel();
        $guests = $userModel->select([
                'user_id',
                'first_name',
                'last_name'
            ])
            ->where('role', 'guest')
            ->findAll();
        return $this->response->setJSON($guests);
    }
    
    public function adminForgotPassword()
    {
        try {
            helper(['form']);

            $rules = [
                'email' => 'required|valid_email'
            ];

            if (!$this->validate($rules)) {
                return $this->response->setJSON(['message' => 'Email is not valid.', 'status' => 'error']);
            }

            $email = $this->request->getVar('email');

            $userModel = new UserModel();

            $user = $userModel->select('
                    user_id,
                    email,
                    CONCAT(first_name, " ", last_name) as fullname,
                ')->where('email', $email)
                ->first();

            if (!$user) {
                return $this->response->setJSON(['message' => 'Email not found.', 'status' => 'error']);
            }

            $resetToken = bin2hex(random_bytes(32));

            $userModel->update($user['user_id'], ['reset_token' => $resetToken]);

            $resetLink = base_url("/admin-password-reset?token=$resetToken");

            $res = $this->sendResetEmail($user['fullname'], $user['email'], $resetLink);
            if (!$res) {
                throw new \Exception("Failed to send reset email.");
            }
            return $this->response->setJSON(['status' => 'success', 'message' => 'Password reset email sent.']);

        } catch (\Throwable $th) {
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to send reset email.']);
        }
    }

    private function sendResetEmail($name, $email, $resetLink)
    {
        try {
            $subject = 'DocuTrack Password Reset';
            $message = "
                <html>
                <head>
                    <title>DocuTrack Password Reset</title>
                </head>
                <body>
                    <p>Dear " . htmlspecialchars($name) . ",</p>
                    <p>You've requested to reset your password for your DocuTrack account. To proceed, please click the link below:</p>
                    <p><a href='" . htmlspecialchars($resetLink) . "' style='background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Reset Your Password</a></p>
                    <p>If you didn't initiate this request, please disregard this email.</p>
                    <p>For assistance, contact the MIS Department.</p>
                    <p>Sincerely,<br>DocuTrack Admin</p>
                </body>
                </html>
            ";

            $emailService = \Config\Services::email();
            $emailService->setTo($email);
            $emailService->setFrom('docutrackonline@gmail.com', 'DocuTrack Online');
            $emailService->setSubject($subject);
            $emailService->setMessage($message);
            $emailService->setMailType('html');

            if (!$emailService->send()) {
                log_message('error', $emailService->printDebugger(['headers', 'subject', 'body']));
                return false;
            }

            return true;
        } catch (\Throwable $th) {
            log_message('error', $th->getMessage());
            return false;
        }
    }

    public function adminPasswordResetPage()
    {
        $resetToken = $this->request->getGet('token');

        if (!$resetToken) {
            return view('errors/html/error_404');
        }

        $userModel = new UserModel();
        $user = $userModel->select(['user_id', 'reset_token'])->where('reset_token', $resetToken)->first();

        if (!$user || $user['reset_token'] != $resetToken) {
            return view('errors/html/error_404');
        }
        return view('Reset', ['user_id' => $user['user_id']]);
    }

    public function checkAdminPasswordReset()
    {
        try {
            $user_id = $this->request->getVar('user_id');
            $password = $this->request->getVar('password');
            $reset_token = $this->request->getVar('token');

            if (!$user_id || !$password || !$reset_token) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request.']);
            }

            $userModel = new UserModel();
            $user = $userModel->select(['user_id', 'email', 'reset_token'])->where(['user_id' => $user_id, 'reset_token' => $reset_token])->first();

            if (!$user || $user['reset_token'] != $reset_token) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request.']);
            }

            // Generate and send OTP
            $otp = rand(100000, 999999); // Generate a 6-digit OTP
            // Store OTP in session for verification later
            session()->set('otp', $otp);

            $this->sendAdminOtp($user['email'], $otp);

            return $this->response->setJSON(['status' => 'success', 'message' => 'OTP sent to your email.']);
        } catch (\Throwable $th) {
            log_message('error', $th->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to send OTP.']);
        }
    }

    private function sendAdminOtp($email, $otp)
    {
        $emailService = \Config\Services::email();
        $emailService->setTo($email);
        $emailService->setFrom('docutrackonline@gmail.com', 'DocuTrack Online');
        $emailService->setSubject('Your OTP Code');
        $emailService->setMessage("Your OTP code is: $otp");
        return $emailService->send();
    }

    public function confirmAdminPasswordReset()
    {
        try {
            $inputOtp = $this->request->getVar('otp');
            $sessionOtp = session()->get('otp');

            $user_id = $this->request->getVar('user_id');
            $password = $this->request->getVar('password');
            $reset_token = $this->request->getVar('token');

            if (!$user_id || !$password || !$reset_token || !$sessionOtp || !$inputOtp) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request.']);
            }

            if ($sessionOtp != $inputOtp) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid OTP.']);
            }

            $userModel = new UserModel();

            $user = $userModel->select(['user_id', 'reset_token'])->where(['user_id' => $user_id, 'reset_token' => $reset_token])->first();

            if (!$user || $user['reset_token'] != $reset_token) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request.']);
            }

            $data = [
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'reset_token' => null,
            ];

            $userModel->update($user_id, $data);

            return $this->response->setJSON(['status' => 'success', 'message' => 'Password reset successfully.']);
        } catch (\Throwable $th) {
            log_message('error', $th->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to reset password.']);
        }
    }

    public function deletepermanently()
    {
        $documentModel = new DocumentModel();

        if ($this->request->isAJAX()) {
            $documentId = $this->request->getPost('documentId');

            $result = $documentModel->delete($documentId);

            if ($result) {
                return $this->response->setJSON(['success' => true]);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete document.']);
            }
        }
    }

}
