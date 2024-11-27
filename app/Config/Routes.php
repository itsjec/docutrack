<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

 $routes->get('/noaccess', function() {
    echo view('noaccess');
});
$routes->get('/', 'AdminController::index');
$routes->get('activitytracker', 'AdminController::activitytracker');
$routes->post('login', 'AdminController::login');
$routes->get('logout', 'AdminController::logout');
$routes->get('reset', 'AdminController::reset');
$routes->get('dashboard', 'AdminController::admindashboard', ['filter' => 'role:admin']);
$routes->get('archived', 'AdminController::archived', ['filter' => 'role:admin']);
$routes->get('all', 'AdminController::alldocuments', ['filter' => 'role:admin']);
$routes->get('viewtransactions', 'AdminController::admintransactions', ['filter' => 'role:admin']);
$routes->get('manageoffice', 'AdminController::adminmanageoffice', ['filter' => 'role:admin']);
$routes->match(['get', 'post'], 'register', 'AdminController::register');
$routes->post('offices/save', 'AdminController::save');
$routes->get('manageuser', 'AdminController::manageuser', ['filter' => 'role:admin']);
$routes->post('users/save', 'AdminController::saveOfficeUser');
$routes->get('manageguest', 'AdminController::manageguest', ['filter' => 'role:admin']);
$routes->post('saveguest', 'AdminController::saveguest');
$routes->get('managedocument', 'AdminController::managedocument', ['filter' => 'role:admin']);
$routes->get('maintenance', 'AdminController::maintenance', ['filter' => 'role:admin']);
$routes->post('classifications/save', 'AdminController::saveClassification', ['as' => 'saveClassification']);
$routes->post('sub-classifications/save', 'AdminController::saveSubClassification');
$routes->post('documents/getSubClassifications', 'AdminController::getSubClassifications');
$routes->get('tracking', 'AdminController::tracking', ['filter' => 'role:admin']);
$routes->get('officetracking', 'AdminController::officetracking', ['filter' => 'role:admin']);
$routes->post('documents/save', 'AdminController::saveDocument');
$routes->post('documents/saveOffice', 'AdminController::saveOfficeDocument');
$routes->get('manageofficedocument', 'AdminController::manageofficedocument', ['filter' => 'role:admin']);
$routes->get('test-insert', 'AdminController::testInsert');
$routes->get('document-status-chart', 'AdminController::documentStatusChart', ['filter' => 'role:admin']);
$routes->post('documents/deleteDocument', 'AdminController::deleteDocument');
$routes->post('admin/update-document-deleted-status/(:num)/(:any)', 'AdminController::updateDocumentDeletedStatus/$1/$2');
$routes->get('admin/delete-document/(:num)', 'AdminController::deleteDocumentpermanent/$1', ['filter' => 'role:admin']);
$routes->post('admin/delete-document/(:num)', 'AdminController::deleteDocumentPermanent/$1');
$routes->post('admin/delete-user/(:num)', 'AdminController::deleteUser/$1');
$routes->post('users/update', 'AdminController::updateUser');
$routes->get('delete/(:num)', 'AdminController::delete/$1', ['filter' => 'role:admin']);
$routes->post('updateguest', 'AdminController::updateGuestUser');
$routes->post('documents/updateDocument', 'AdminController::updateDocument');
$routes->post('documents/updateGuestDocument', 'AdminController::updateGuestDocument');
$routes->post('documents/getDocument', 'AdminController::getDocument');
$routes->get('search', 'AdminController::search', ['filter' => 'role:admin']);
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
$routes->get('adminkiosk', 'AdminController::adminindex');
$routes->post('searchResults', 'AdminController::searchResults');
$routes->get('viewdetails', 'AdminController::viewdetails');
$routes->post('user/activate', 'AdminController::activateUser');
$routes->post('user/deactivate', 'AdminController::deactivateUser');
$routes->get('/delete-user/(:num)', 'AdminController::deleteUser/$1');
$routes->get('user/manage', 'AdminController::manageUsers');
$routes->get('officelist', 'AdminController::getOfficeList');
$routes->get('getGuestList', 'AdminController::getGuestList');
$routes->get('/admin-password-reset', 'AdminController::adminPasswordResetPage');
$routes->post('/admin-check-password-reset', 'AdminController::checkAdminPasswordReset');
$routes->post('/admin-confirm-password-reset', 'AdminController::confirmAdminPasswordReset');
$routes->post('/admin-forgot-password', 'AdminController::adminForgotPassword');
$routes->post('documents/deleteDocument', 'AdminController::deleteDocument');





$routes->get('index', 'OfficeController::index', ['filter' => 'role:office user']);
$routes->get('pending', 'OfficeController::pending', ['filter' => 'role:office user']);
$routes->get('received', 'OfficeController::received', ['filter' => 'role:office user']);
$routes->get('ongoing', 'OfficeController::ongoing', ['filter' => 'role:office user']);
$routes->get('completed', 'OfficeController::completed', ['filter' => 'role:office user']);
$routes->get('history', 'OfficeController::history', ['filter' => 'role:office user']);
$routes->get('allDocuments', 'OfficeController::allDocuments', ['filter' => 'role:office user']);
$routes->get('manageprofile', 'OfficeController::manageprofile', ['filter' => 'role:office user']);
$routes->get('adddepartmentdocument', 'OfficeController::adddocumentdepartment', ['filter' => 'role:office user']);
$routes->get('addclientdocument', 'OfficeController::adddocumentclient', ['filter' => 'role:office user']);
$routes->post('/office/updateProfile', 'OfficeController::updateProfile');
$routes->get('trash', 'OfficeController::trash', ['filter' => 'role:office user']);
$routes->get('incoming', 'OfficeController::incoming', ['filter' => 'role:office user']);
$routes->get('documents/getDocumentInfo', 'OfficeController::getDocumentInfo');
$routes->post('documents/updateStatus', 'OfficeController::updateStatus');
$routes->post('documents/updateProcessStatus', 'OfficeController::updateProcessStatus');
$routes->post('documents/updateCompletedStatus', 'OfficeController::updateCompletedStatus');
$routes->post('documents/deleteDocument', 'OfficeController::deleteDocument');
$routes->post('documents/sendOutDocument', 'OfficeController::sendOutDocument');
$routes->get('office/getOffices', 'OfficeController::getOffices', ['filter' => 'role:office user']);
$routes->post('documents/update-document-status/(:num)/(:segment)', 'OfficeController::updateDocumentStatus/$1/$2');
$routes->post('documents/update-document-completed-status/(:num)/(:segment)', 'OfficeController::updateDocumentCompletedStatus/$1/$2');
$routes->post('documents/update-document-deleted-status/(:num)/(:segment)', 'OfficeController::updateDocumentDeletedStatus/$1/$2');
$routes->post('documents/update-document-recipient-and-status/(:num)/(:num)/(:segment)', 'OfficeController::updateDocumentRecipientAndStatus/$1/$2/$3');
$routes->delete('documents/delete/(:num)', 'OfficeController::deleteDocument/$1');
$routes->post('deleteDocument/(:num)', 'OfficeController::deleteDocument/$1');
$routes->get('searchDocu', 'OfficeController::search', ['filter' => 'role:office user']);
$routes->get('documents/getDocumentDetails/(:num)', 'OfficeController::getDocumentDetails/$1');
$routes->post('generate-qr-code', 'OfficeController::generate');
$routes->post('documents/getSubClassifications', 'OfficeController::getSubClassifications');
$routes->post('documents/saveClient', 'OfficeController::saveClientDocument');
$routes->post('documents/saveDepartment', 'OfficeController::saveDepartmentDocument');
$routes->get('departmenttracking', 'OfficeController::departmenttracking', ['filter' => 'role:office user']);
$routes->get('clienttracking', 'OfficeController::clienttracking', ['filter' => 'role:office user']);
$routes->post('documents/updateDeptDocument', 'OfficeController::updateDocument');
$routes->post('documents/updateClientDocument', 'OfficeController::updateGuestDocument');
$routes->post('documents/archiveDocument', 'OfficeController::archiveDocument');
$routes->post('documents/archiveClientDocument', 'OfficeController::archiveClientDocument');
$routes->get('officemaintenance', 'OfficeController::officemaintenance', ['filter' => 'role:office user']);
$routes->post('office/updateDepartmentClassification', 'OfficeController::updateDepartmentClassification');
$routes->post('classifications/update', 'OfficeController::updateClassification');
$routes->post('docuclassifications/save', 'OfficeController::saveDocuClassification', ['as' => 'saveDocuClassification']);
$routes->post('docusub-classifications/save', 'OfficeController::saveSubClassification');
$routes->get('manageofficeuser', 'OfficeController::manageofficeuser', ['filter' => 'role:office user']);
$routes->get('manageclient', 'OfficeController::manageclient', ['filter' => 'role:office user']);
$routes->post('officeusers/update', 'OfficeController::updateOfficeUser');
$routes->post('officeusers/save', 'OfficeController::saveOfficeUser');
$routes->get('manageofficeguest', 'OfficeController::manageguest', ['filter' => 'role:office user']);
$routes->post('saveofficeguest', 'OfficeController::saveofficeguest');
$routes->post('updateofficeguest', 'OfficeController::updateUser');
$routes->post('user/activateUser', 'OfficeController::activateguestUser');
$routes->post('user/deactivateUser', 'OfficeController::deactivateguestUser');
$routes->get('/testlogging', 'OfficeController::testLogging');
$routes->get('officelist', 'OfficeController::getOfficeList');
$routes->get('getGuestList', 'OfficeController::getGuestList');







//ha    tdog
$routes->get('track', 'UserController::track');
//hagtod

$routes->get('userindex', 'UserController::index');
$routes->post('searchResults', 'UserController::searchResults');
$routes->get('indexloggedin', 'UserController::indexloggedin');
$routes->post('adminsearchResults', 'UserController::searchResults', ['as' => 'adminsearchResults']);
$routes->get('viewdetails', 'UserController::viewdetails');
$routes->get('adminviewdetails', 'UserController::viewdetails');

$routes->post('searchguestResults', 'UserController::guestsearchResults', ['as' => 'searchguestResults']);
$routes->get('guestviewdetails', 'UserController::guestviewdetails');
$routes->post('guestsearchResults', 'UserController::guestsearchResults');
$routes->get('transactions', 'UserController::transaction');




$routes->get('test-insert-document-history', 'OfficeController::testInsertDocumentHistory');






$routes->get('/qr-code', 'QrCodeController::index');
$routes->post('/qr-code/generate', 'QrCodeController::generate');

$routes->match(['get', 'post'], 'qr-codes', 'QrCodeGeneratorController::index');



$routes->get('file/(:segment)', 'FileController::serve/$1');


$routes->get('notification', 'NotificationController::index');
$routes->post('notification/generate_token', 'NotificationController::generate_token');

$routes->post('notification/send_notification', 'NotificationController::send_notification');
$routes->post('notification/store_token', 'NotificationController::store_token');
$routes->post('store-document-token', 'NotificationController::storeDocumentAndAssignToken');
