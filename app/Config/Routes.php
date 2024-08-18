<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'AdminController::index');
$routes->post('login', 'AdminController::login');
$routes->get('logout', 'AdminController::logout');
$routes->get('dashboard', 'AdminController::admindashboard', ['filter' => 'authfilter']);
$routes->get('archived', 'AdminController::archived', ['filter' => 'authfilter']);
$routes->get('all', 'AdminController::alldocuments', ['filter' => 'authfilter']);
$routes->get('viewtransactions', 'AdminController::admintransactions', ['filter' => 'authfilter']);
$routes->get('manageoffice', 'AdminController::adminmanageoffice', ['filter' => 'authfilter']);
$routes->match(['get', 'post'], 'register', 'AdminController::register');
$routes->post('offices/save', 'AdminController::save');
$routes->get('manageuser', 'AdminController::manageuser', ['filter' => 'authfilter']);
$routes->post('users/save', 'AdminController::saveOfficeUser');
$routes->get('manageguest', 'AdminController::manageguest', ['filter' => 'authfilter']);
$routes->post('saveguest', 'AdminController::saveguest');
$routes->get('managedocument', 'AdminController::managedocument', ['filter' => 'authfilter']);
$routes->get('maintenance', 'AdminController::maintenance', ['filter' => 'authfilter']);
$routes->post('classifications/save', 'AdminController::saveClassification', ['as' => 'saveClassification']);
$routes->post('sub-classifications/save', 'AdminController::saveSubClassification');
$routes->post('documents/getSubClassifications', 'AdminController::getSubClassifications');
$routes->get('tracking', 'AdminController::tracking', ['filter' => 'authfilter']);
$routes->get('officetracking', 'AdminController::officetracking', ['filter' => 'authfilter']);
$routes->post('documents/save', 'AdminController::saveDocument');
$routes->post('documents/saveOffice', 'AdminController::saveOfficeDocument');
$routes->get('manageofficedocument', 'AdminController::manageofficedocument', ['filter' => 'authfilter']);
$routes->get('test-insert', 'AdminController::testInsert');
$routes->get('document-status-chart', 'AdminController::documentStatusChart', ['filter' => 'authfilter']);
$routes->post('documents/deleteDocument', 'AdminController::deleteDocument');
$routes->post('admin/update-document-deleted-status/(:num)/(:any)', 'AdminController::updateDocumentDeletedStatus/$1/$2');
$routes->get('admin/delete-document/(:num)', 'AdminController::deleteDocumentpermanent/$1', ['filter' => 'authfilter']);
$routes->post('admin/delete-document/(:num)', 'AdminController::deleteDocumentPermanent/$1');
$routes->post('admin/delete-user/(:num)', 'AdminController::deleteUser/$1');
$routes->post('users/update', 'AdminController::updateUser');
$routes->get('delete/(:num)', 'AdminController::delete/$1', ['filter' => 'authfilter']);
$routes->post('updateguest', 'AdminController::updateGuestUser');
$routes->post('documents/updateDocument', 'AdminController::updateDocument');
$routes->post('documents/updateGuestDocument', 'AdminController::updateGuestDocument');
$routes->post('documents/getDocument', 'AdminController::getDocument');
$routes->get('search', 'AdminController::search', ['filter' => 'authfilter']);
$routes->match(['get', 'post'], 'admin/transactions/download', 'AdminController::download_all_rows');
$routes->get('document/aging', 'AdminController::aging');
$routes->get('office-processing-time', 'AdminController::officeProcessingTime');
$routes->get('document/all-versions-modal/(:num)', 'AdminController::allVersionsModal/$1');
$routes->post('updateOfficeName', 'AdminController::updateOfficeName');
$routes->post('office/updateStatus', 'AdminController::updateStatus');
$routes->post('office/updateClassification', 'AdminController::updateClassification');
$routes->post('sub-classifications/update', 'AdminController::updateClassificationName');
$routes->post('deactivateUser', 'AdminController::deactivateUser');
$routes->get('documents/fetchVersionsByTitle', 'AdminController::fetchVersionsByTitle');
$routes->get('kiosk', 'AdminController::kiosk');
$routes->get('searchkiosk', 'AdminController::searchkiosk');

















$routes->get('index', 'OfficeController::index', ['filter' => 'authfilter']);
$routes->get('pending', 'OfficeController::pending', ['filter' => 'authfilter']);
$routes->get('received', 'OfficeController::received', ['filter' => 'authfilter']);
$routes->get('ongoing', 'OfficeController::ongoing', ['filter' => 'authfilter']);
$routes->get('completed', 'OfficeController::completed', ['filter' => 'authfilter']);
$routes->get('history', 'OfficeController::history', ['filter' => 'authfilter']);
$routes->get('allDocuments', 'OfficeController::allDocuments', ['filter' => 'authfilter']);
$routes->get('manageprofile', 'OfficeController::manageprofile', ['filter' => 'authfilter']);
$routes->get('adddepartmentdocument', 'OfficeController::adddocumentdepartment', ['filter' => 'authfilter']);
$routes->get('addclientdocument', 'OfficeController::adddocumentclient', ['filter' => 'authfilter']);
$routes->post('/office/updateProfile', 'OfficeController::updateProfile');
$routes->get('trash', 'OfficeController::trash', ['filter' => 'authfilter']);
$routes->get('incoming', 'OfficeController::incoming', ['filter' => 'authfilter']);
$routes->get('documents/getDocumentInfo', 'OfficeController::getDocumentInfo');
$routes->post('documents/updateStatus', 'OfficeController::updateStatus');
$routes->post('documents/updateProcessStatus', 'OfficeController::updateProcessStatus');
$routes->post('documents/updateCompletedStatus', 'OfficeController::updateCompletedStatus');
$routes->post('documents/deleteDocument', 'OfficeController::deleteDocument');
$routes->post('documents/sendOutDocument', 'OfficeController::sendOutDocument');
$routes->get('office/getOffices', 'OfficeController::getOffices', ['filter' => 'authfilter']);
$routes->post('documents/update-document-status/(:num)/(:segment)', 'OfficeController::updateDocumentStatus/$1/$2');
$routes->post('documents/update-document-completed-status/(:num)/(:segment)', 'OfficeController::updateDocumentCompletedStatus/$1/$2');
$routes->post('documents/update-document-deleted-status/(:num)/(:segment)', 'OfficeController::updateDocumentDeletedStatus/$1/$2');
$routes->post('documents/update-document-recipient-and-status/(:num)/(:num)/(:segment)', 'OfficeController::updateDocumentRecipientAndStatus/$1/$2/$3');
$routes->delete('documents/delete/(:num)', 'OfficeController::deleteDocument/$1');
$routes->post('deleteDocument/(:num)', 'OfficeController::deleteDocument/$1');
$routes->get('searchDocu', 'OfficeController::search', ['filter' => 'authfilter']);
$routes->get('documents/getDocumentDetails/(:num)', 'OfficeController::getDocumentDetails/$1');
$routes->post('generate-qr-code', 'OfficeController::generate');
$routes->post('documents/getSubClassifications', 'OfficeController::getSubClassifications');
$routes->post('documents/saveClient', 'OfficeController::saveClientDocument');
$routes->post('documents/saveDepartment', 'OfficeController::saveDepartmentDocument');
$routes->get('departmenttracking', 'OfficeController::departmenttracking', ['filter' => 'authfilter']);
$routes->get('clienttracking', 'OfficeController::clienttracking', ['filter' => 'authfilter']);
$routes->post('documents/updateDeptDocument', 'OfficeController::updateDocument');
$routes->post('documents/updateClientDocument', 'OfficeController::updateGuestDocument');
$routes->post('documents/archiveDocument', 'OfficeController::archiveDocument');
$routes->post('documents/archiveClientDocument', 'OfficeController::archiveClientDocument');
$routes->get('officemaintenance', 'OfficeController::officemaintenance', ['filter' => 'authfilter']);
$routes->post('office/updateDepartmentClassification', 'OfficeController::updateDepartmentClassification');
$routes->post('classifications/update', 'OfficeController::updateClassification');
$routes->post('docuclassifications/save', 'OfficeController::saveDocuClassification', ['as' => 'saveDocuClassification']);
$routes->post('docusub-classifications/save', 'OfficeController::saveSubClassification');
$routes->get('manageofficeuser', 'OfficeController::manageofficeuser', ['filter' => 'authfilter']);
$routes->get('manageclient', 'OfficeController::manageclient', ['filter' => 'authfilter']);
$routes->post('officeusers/update', 'OfficeController::updateOfficeUser');
$routes->post('officeusers/save', 'OfficeController::saveOfficeUser');














//ha    tdog
$routes->get('track', 'UserController::track');
//hagtod

$routes->get('userindex', 'UserController::index', ['filter' => 'authfilter']);
$routes->post('searchResults', 'UserController::searchResults');
$routes->get('indexloggedin', 'UserController::indexloggedin', ['filter' => 'authfilter']);
$routes->post('adminsearchResults', 'UserController::searchResults', ['as' => 'adminsearchResults']);
$routes->get('viewdetails', 'UserController::viewdetails');
$routes->get('adminviewdetails', 'UserController::viewdetails', ['filter' => 'authfilter']);

$routes->post('searchguestResults', 'UserController::guestsearchResults', ['as' => 'searchguestResults']);
$routes->get('guestviewdetails', 'UserController::guestviewdetails', ['filter' => 'authfilter']);
$routes->post('guestsearchResults', 'UserController::guestsearchResults');
$routes->get('transactions', 'UserController::transaction', ['filter' => 'authfilter']);




$routes->get('test-insert-document-history', 'OfficeController::testInsertDocumentHistory');






$routes->get('/qr-code', 'QrCodeController::index');
$routes->post('/qr-code/generate', 'QrCodeController::generate');

$routes->match(['get', 'post'], 'qr-codes', 'QrCodeGeneratorController::index');

