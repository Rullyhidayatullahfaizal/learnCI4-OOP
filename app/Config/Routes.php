<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/login', 'WebController::login'); 
$routes->get('/register', 'WebController::register'); 
$routes->get('/management', 'WebController::management'); 




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

// routes subscriptionChange;
$routes->group("api",['filter' => 'auth'],function($routes){
    
      $routes->get('subscription-changes', 'SubscriptionChangeController::index');
    $routes->get('subscription-changes/(:num)', 'SubscriptionChangeController::show/$1');
    $routes->post('subscription-changes', 'SubscriptionChangeController::create');
    $routes->put('subscription-changes/(:num)', 'SubscriptionChangeController::update/$1');
    $routes->delete('subscription-changes/(:num)', 'SubscriptionChangeController::delete/$1');
});