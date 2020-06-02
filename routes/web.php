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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'courses'], function () use ($router) {
    $router->get('', 'CourseController@index');
    $router->post('',[
    	'middleware'=> 'course',
    	'uses'=>'CourseController@store'
    ]);
    $router->put('{id}',[
    	'middleware'=> 'course',
    	'uses'=>'CourseController@update'
    ]);
    $router->get('all', 'CourseController@index');
    $router->get('{id}', 'CourseController@show');
    $router->delete('{id}', 'CourseController@destroy');
});

$router->group(['prefix' => 'students'], function () use ($router) {
    $router->get('', 'StudentController@index');
    $router->post('',[
    	'middleware'=> 'student',
    	'uses'=>'StudentController@store'
    ]);
    $router->put('{id}',[
    	'middleware'=> 'student',
    	'uses'=>'StudentController@update'
    ]);
    $router->get('all', 'StudentController@index');
    $router->get('{id}', 'StudentController@show');
    $router->delete('{id}', 'StudentController@destroy');
});
