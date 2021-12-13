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
    $router->get('bnb/getAllProperties/{limit}',  ['uses' => 'BnbController@getAllProperties']);
    $router->get('bnb/getPropertyById/{property_id}',  ['uses' => 'BnbController@getPropertyById']);
    $router->get('bnb/getAllPropertyByAddress',  ['uses' => 'BnbController@getAllPropertyByAddress']);
    
    $router->post('bnb/operationFromApp',  ['uses' => 'BnbController@operationFromApp']);
    $router->post('bnb/saveEmails',  ['uses' => 'bnbController@saveEmails']);
    $router->get('bnb/getAllTags',  ['uses' => 'BnbController@getAllTags']);
    
    $router->post('user/addNewUser',  ['uses' => 'UserController@addNewUser']);
    $router->post('user/updateUser',  ['uses' => 'UserController@updateUser']);
    $router->post('user/loginUser',  ['uses' => 'UserController@loginUser']);
    $router->get('user/getUserById/{id}',  ['uses' => 'UserController@getUserById']);
    $router->get('user/getAllUser',  ['uses' => 'UserController@getAllUser']);
    // $router->post('bnb/revenueAvailableAction/{property_id}',  ['uses' => 'BnbController@revenueAvailableAction']);
    // $router->get('/key', function() {
    //     return \Illuminate\Support\Str::random(32);
    // });
  });