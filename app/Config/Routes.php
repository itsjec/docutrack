<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'AdminController::index');
$routes->post('login', 'AdminController::login');
$routes->get('dashboard', 'AdminController::admindashboard');
$routes->get('archived', 'AdminController::archived');
$routes->get('viewtransactions', 'AdminController::admintransactions');
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
$routes->get('test-insert', 'AdminController::testInsert');






$routes->get('index', 'OfficeController::index');
$routes->get('pending', 'OfficeController::pending');
$routes->get('received', 'OfficeController::received');
$routes->get('ongoing', 'OfficeController::ongoing');
$routes->get('completed', 'OfficeController::completed');
$routes->get('history', 'OfficeController::history');
$routes->get('manageprofile', 'OfficeController::manageprofile');
$routes->get('trash', 'OfficeController::trash');
$routes->get('incoming', 'OfficeController::incoming');
$routes->get('documents/getDocumentInfo', 'OfficeController::getDocumentInfo');
$routes->post('documents/updateStatus', 'OfficeController::updateStatus');
$routes->post('documents/updateProcessStatus', 'OfficeController::updateProcessStatus');
$routes->post('documents/updateCompletedStatus', 'OfficeController::updateCompletedStatus');
$routes->post('documents/deleteDocument', 'OfficeController::deleteDocument');
$routes->post('documents/sendOutDocument', 'OfficeController::sendOutDocument');
$routes->get('office/getOffices', 'OfficeController::getOffices');
$routes->post('documents/update-document-status/(:num)/(:segment)', 'OfficeController::updateDocumentStatus/$1/$2');
$routes->post('documents/update-document-completed-status/(:num)/(:segment)', 'OfficeController::updateDocumentCompletedStatus/$1/$2');
$routes->post('documents/update-document-deleted-status/(:num)/(:segment)', 'OfficeController::updateDocumentDeletedStatus/$1/$2');
$routes->post('documents/update-document-recipient-and-status/(:num)/(:num)/(:segment)', 'OfficeController::updateDocumentRecipientAndStatus/$1/$2/$3');
$routes->delete('documents/delete/(:num)', 'OfficeController::deleteDocument/$1');



$routes->get('userindex', 'UserController::index');


$routes->get('test-insert-document-history', 'OfficeController::testInsertDocumentHistory');

