<?php

namespace App\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTServices
{
    private $key = 'docutrack0129scrtKeY'; 

    public function generateToken($userData)
    {
        $issuedAt = time();
        $expiration = $issuedAt + 3600; 

        $payload = array_merge($userData, [
            'iat' => $issuedAt,
            'exp' => $expiration
        ]);

        return JWT::encode($payload, $this->key, 'HS256');
    }

    public function decodeToken($token)
    {
        try {
            return JWT::decode($token, new Key($this->key, 'HS256'));
        } catch (\Exception $e) {
            return false; 
        }
    }
}
