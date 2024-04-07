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
$routes->get('manageoffice', 'AdminController::manageoffice');
$routes->get('manageprofile', 'AdminController::manageprofile');
$routes->get('manageusers', 'AdminController::manageusers');
$routes->get('managedocument', 'AdminController::managedocument');
$routes->get('viewtransactions', 'AdminController::viewtransactions');
$routes->get('archiveddocuments', 'AdminController::archiveddocuments');
