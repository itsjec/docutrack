<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'AdminController::index');
$routes->post('login', 'AdminController::login');
$routes->get('dashboard', 'AdminController::admindashboard');
$routes->match(['get', 'post'], 'register', 'AdminController::register');
