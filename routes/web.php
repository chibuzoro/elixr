<?php

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


$router->group(['prefix' => 'api', 'namespace'=> '\App\Http\Controllers'], static function() use ($router){

    $router->post('{version}/{resource}', 'ApiController@doPostAction');

    $router->get('{version}/{resource}[/{id}]', 'ApiController@doGetAction');

    $router->put('{version}/{resource}[/{id}]', 'ApiController@doUpdateAction');

    $router->delete('{version}/{resource}/{id}', 'ApiController@doDeleteAction');


});








