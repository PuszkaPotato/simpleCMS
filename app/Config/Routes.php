<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Contact Form
$routes->post('/contact-us/send', 'Contact::Send');

// Main sub-pages

//Blog
$routes->get('/blog', 'Blog::index');
$routes->get('/blog/view/(:any)', 'Blog::view/$1');
$routes->addRedirect('/blog/.*/', '/blog');

//Gallery
$routes->get('/portfolio', 'Gallery::portfolio');

$routes->get('(:any)', 'Pages::view/$1', ['priority' => 2]);

// Admin Panel Routes
$routes->group('admin', function($routes) {
	$routes->add('/', 'Admin::index');
	$routes->add('(:any)', 'Admin::view/$1', ['priority' => 1]);
	$routes->add('login', 'Login::index');
	$routes->add('logout', 'Login::logout');
	$routes->post('login/auth', 'Login::auth');

	$routes->group('users', function($routes) {
		$routes->post('createUser', 'Admin::createUser');
		$routes->post('updateUser', 'Admin::updateUser');
		$routes->post('deleteUser', 'Admin::deleteUser');
	});

	$routes->group('profile', function($routes) {
		$routes->get('', 'Profile::index');
		$routes->post('changepassword', 'Profile::changePassword');
		$routes->post('update', 'Profile::updateProfile');
	});

	$routes->group('news', function($routes) {
		$routes->post('createNews', 'Admin::createNews');
		$routes->post('editNews', 'Admin::editNews');
		$routes->post('deleteNews', 'Admin::deleteNews');
	});

	$routes->group('gallery', function($routes) {
		$routes->get('', 'Gallery::index');
		$routes->get('(:any)', 'Gallery::view/$1');
		$routes->post('createAlbum', 'Gallery::createAlbum');
		$routes->post('editAlbum', 'Gallery::editAlbum');
		$routes->post('deleteAlbum', 'Gallery::deleteAlbum');
		$routes->post('deletePicture', 'Gallery::deletePicture');
	});

	$routes->group('settings', function($routes) {
		$routes->post('updateSettings', 'Admin::updateSettings');
		$routes->post('updateEmail', 'Admin::updateEmail');
	});
});

// Contact Form
$routes->post('/contact-us/send', 'Contact::Send');
// Main sub-pages
$routes->get('/blog', 'Blog::index');
$routes->get('/blog/view/(:any)', 'Blog::view/$1');
$routes->addRedirect('/blog/.*/', '/blog');
$routes->get('/(:any)', 'Pages::view/$1', ['priority' => 1]);
