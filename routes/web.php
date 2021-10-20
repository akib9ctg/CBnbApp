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
    $router->post('bnb/operationFromApp',  ['uses' => 'BnbController@operationFromApp']);
    // $router->post('bnb/notAvailableAction/{property_id}/{status}',  ['uses' => 'BnbController@NotAvailableAction']);
    // $router->get('bnb/getTagByPropertyId/{property_id}',  ['uses' => 'BnbController@getTagByPropertyId']);
    // $router->post('bnb/revenueAvailableAction/{property_id}',  ['uses' => 'BnbController@revenueAvailableAction']);

  });