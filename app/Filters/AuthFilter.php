<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Controllers\JWTServices; // Adjust the namespace if needed
use Config\Services; // Import the Services class for other configurations

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $jwtService = new JWTServices();
        
        // Retrieve the JWT token from session
        $session = session();
        $token = $session->get('jwt_token');

        if ($token) {
            // Decode the token and log the result
            $decoded = $jwtService->decodeToken($token);

            if ($decoded) {
                log_message('info', 'Valid JWT token retrieved from session.');
                // Optionally set user data to request or session
                return;
            } else {
                log_message('error', 'Invalid JWT token retrieved from session.');
            }
        } else {
            log_message('warning', 'No JWT token found in session.');
        }

        // Redirect to login if the token is missing or invalid
        return redirect()->to('/'); // Adjust the redirect as needed
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here if needed
    }
}
