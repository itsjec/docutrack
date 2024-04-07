<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class AdminController extends BaseController
{
    public function index()
    {
        return view('LogIn');
    }

    public function __construct()
    {
        $this->session = \Config\Services::session();
    }

    public function dashboard()
    {
        return view('Admin/Dashboard');
    }   
    
    public function login()
    {
        // Load the necessary libraries and helpers
        helper('form');
        helper('url');
        helper('cookie');

        $userModel = new \App\Models\UserModel();

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $userModel->where('email', $email)->first();

        if (!$user || !password_verify($password, $user['password'])) {
            return redirect()->to(site_url('/'))->with('error', 'Invalid email or password');
        }

        switch ($user['role']) {
            case 'guest':
                return redirect()->to('guest'); 
            case 'admin':
                return redirect()->to('dashboard'); 
            case 'office_user':
                $office_id = $user['office_id'];
                return redirect()->to('office')->with('office_id', $office_id); 
            default:
                return redirect()->to('default'); 
        }
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
        return view('Admin/ManageOffice');
    } 

    public function manageprofile()
    {
        return view('Admin/ManageProfile');
    } 

    public function manageusers()
    {
        return view('Admin/ManageUsers');
    } 

    public function managedocument()
    {
        return view('Admin/ManageDocument');
    } 

    public function viewtransactions()
    {
        return view('Admin/ViewTransactions');
    } 

    public function archiveddocuments()
    {
        return view('Admin/ArchivedDocuments');
    } 
    
}
