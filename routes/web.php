<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('login',[
        'uses' => 'LoginController@login'
    ]);
});

$router->group(['prefix' => 'api', 'middleware' => 'jwt'], function () use ($router) {
    $router->post('profile/update', ['uses' => 'ProfileController@update']);
    $router->post('profile/password-reset', ['uses' => 'ProfileController@password_reset']);
  
    $router->group(['prefix' => 'customers'], function () use ($router) {
        $router->get('/', ['uses' => 'CustomersController@get']);
        $router->post('create', ['uses' => 'CustomersController@create']);
        $router->post('edit/{id}', ['uses' => 'CustomersController@edit']);
        $router->delete('delete/{id}', ['uses' => 'CustomersController@delete']);
    });

});