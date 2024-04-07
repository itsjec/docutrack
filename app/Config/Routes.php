<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'AdminController::index');
$routes->post('login', 'AdminController::login');
$routes->get('dashboard', 'AdminController::admindashboard');
$routes->get('manageoffice', 'AdminController::adminmanageoffice');
$routes->match(['get', 'post'], 'register', 'AdminController::register');
$routes->post('offices/save', 'AdminController::save');
$routes->get('manageuser', 'AdminController::manageuser');
$routes->post('users/save', 'AdminController::saveOfficeUser');
$routes->get('manageguest', 'AdminController::manageguest');
$routes->post('saveguest', 'AdminController::saveguest');
$routes->get('managedocument', 'AdminController::managedocument');
$routes->get('maintenance', 'AdminController::maintenance');
$routes->post('classifications/save', 'AdminController::saveClassification', ['as' => 'saveClassification']);
$routes->post('sub-classifications/save', 'AdminController::saveSubClassification');
$routes->post('documents/getSubClassifications', 'AdminController::getSubClassifications');
$routes->get('tracking', 'AdminController::tracking');
$routes->post('documents/save', 'AdminController::saveDocument');
$routes->post('documents/saveOffice', 'AdminController::saveOfficeDocument');
$routes->get('manageofficedocument', 'AdminController::manageofficedocument');




$routes->get('index', 'OfficeController::index');
$routes->get('pending', 'OfficeController::pending');
$routes->get('ongoing', 'OfficeController::ongoing');
$routes->get('completed', 'OfficeController::completed');
$routes->get('history', 'OfficeController::history');
$routes->get('manageprofile', 'OfficeController::manageprofile');
$routes->get('trash', 'OfficeController::trash');



