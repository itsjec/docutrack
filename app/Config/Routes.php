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



