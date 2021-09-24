<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);
$routes->setPrioritize();

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.

// Index Route
$routes->get('/', 'Home::index');

// Admin Panel Routes
$routes->get('/admin', 'Admin::index');
$routes->get('/admin/login', 'Login::index');
$routes->get('/admin/(:any)', 'Admin::view/$1', ['priority' => 1]);
$routes->post('/admin/login/auth', 'Login::login');
$routes->post('/admin/users/createUser', 'Admin::createUser');
$routes->post('/admin/users/updateUser', 'Admin::updateUser');
$routes->post('/admin/users/deleteUser', 'Admin::deleteUser');
$routes->post('/admin/updateSettings', 'Admin::updateSettings');
$routes->post('/admin/news/createNews', 'Admin::createNews');
$routes->post('/admin/news/editNews', 'Admin::editNews');
$routes->post('/admin/news/deleteNews', 'Admin::deleteNews');

// Contact Form
$routes->post('/contact-us/send', 'Contact::Send');
// Main sub-pages
$routes->get('/(:any)', 'Pages::view/$1', ['priority' => 1]);

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
