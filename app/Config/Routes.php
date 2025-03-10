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

   $routes->group("customers",['filter' => 'auth'],function($routes){
    //    $routes->resource('users');
    $routes-> get('/',"CustomerController::index");
    $routes-> get('(:num)',"CustomerController::show/$1");
    $routes->put('(:num)', 'CustomerController::update/$1');
    $routes->delete('(:num)', 'CustomerController::delete/$1');
   });


});