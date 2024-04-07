<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\OfficeModel;

class AdminController extends BaseController
{
    public function index()
    {
        return view('LogIn');
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
    // Load the UserModel
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
                'role' => $user['role'],
                'office_id' => $user['office_id'] // Assuming office_id is a field in the users table
            ];

            // Set session
            session()->set($userData);

            // Redirect based on user role
            switch ($user['role']) {
                case 'admin':
                    return redirect()->to('dashboard');
                    break;
                case 'office_user':
                    return redirect()->to('office');
                    break;
                case 'guest':
                default:
                    return redirect()->to('/');
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
                    'image' => $imagePath, 
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
        $data['guestUsers'] = $userModel->select('first_name, last_name, email, image')
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
        'role' => 'office_user',
    ];

    $userModel->insert($userData);

    return redirect()->to('manageuser')->with('success', 'Office user added successfully.');
}

    
}
