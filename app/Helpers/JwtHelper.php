<?php

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

function generateJWT($userData) {
    $key = getenv('JWT_SECRET');
    $payload = [
        'iss' => base_url(),
        'aud' => base_url(),
        'iat' => time(),
        'exp' => time() + 3600, 
        'data' => $userData
    ];

    return JWT::encode($payload, $key, 'HS256');
}

function validateJWT($token) {
    $key = getenv('JWT_SECRET');

    try {
        $decoded = JWT::decode($token, new Key($key, 'HS256'));
        return (array) $decoded->data;
    } catch (Exception $e) {
        return null;
    }
}