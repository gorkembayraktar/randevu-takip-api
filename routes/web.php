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
   
    $router->group(['prefix' => 'profile'], function () use ($router) {
        $router->post('update', ['uses' => 'ProfileController@update']);
        $router->post('password-reset', ['uses' => 'ProfileController@password_reset']);
    });

  
    $router->group(['prefix' => 'customers'], function () use ($router) {
        $router->get('/', ['uses' => 'CustomersController@get']);
        $router->post('create', ['uses' => 'CustomersController@create']);
        $router->post('edit/{id}', ['uses' => 'CustomersController@edit']);
        $router->delete('{id}', ['uses' => 'CustomersController@delete']);
    });

    $router->group(['prefix' => 'appointment-hours'], function () use ($router) {
        $router->get('/', ['uses' => 'AppointmentHourController@get']);

        $router->post('create', ['uses' => 'AppointmentHourController@create']);
        $router->delete('{id}', ['uses' => 'AppointmentHourController@delete']);
        $router->post('active/{id}', ['uses' => 'AppointmentHourController@active']);
        $router->get('{id}', ['uses' => 'AppointmentHourController@find']);

    });

    $router->group(['prefix' => 'holidays'], function () use ($router) {
        $router->get('/', ['uses' => 'HolidaysController@get']);
        $router->post('create', ['uses' => 'HolidaysController@create']);
        $router->post('edit/{id}', ['uses' => 'HolidaysController@edit']);
        $router->delete('{id}', ['uses' => 'HolidaysController@delete']);
 

        $router->get('{id}', ['uses' => 'HolidaysController@find']);
    });

});