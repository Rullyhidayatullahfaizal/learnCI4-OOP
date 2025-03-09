<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// routes user
$routes->group('api',function($routes){
   $routes->post('register', 'AuthController::register'); 
   $routes->post('login', 'AuthController::login');

   $routes->group("",['filter' => 'auth'],function($routes){
       $routes->resource('users');
       $routes->resource('customers');
   });
});