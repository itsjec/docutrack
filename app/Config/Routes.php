<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'AdminController::index');
$routes->post('login', 'AdminController::login');
$routes->get('dashboard', 'AdminController::dashboard');
$routes->match(['get', 'post'], 'register', 'AdminController::register');
$routes->get('officedashboard', 'OfficeController::index');

