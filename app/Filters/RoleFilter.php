<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Retrieve user data from the session
        $session = session();
        $role = $session->get('role');

        // Check if user is logged in and has the correct role
        if (!$role) {
            return redirect()->to('/')->with('error', 'You need to log in first.');
        }

        // Check if the user's role matches the required role(s)
        if ($arguments && !in_array($role, $arguments)) {
            return redirect()->to('/noaccess')->with('error', 'Access denied. You do not have permission.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed after request
    }
}