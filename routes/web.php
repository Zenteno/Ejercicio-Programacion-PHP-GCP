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

$router->get('/token', 'TokenController@tokenizer');

$router->group(['prefix' => 'courses', "middleware"=>"jwt"], function () use ($router) {
    $router->group(['middleware'=>'course'],function () use ($router){
        $router->get('', 'CourseController@indexPaginated');
        $router->get('all', 'CourseController@index');
        $router->get('{id}', 'CourseController@show');
        $router->delete('{id}', 'CourseController@destroy');
        $router->post('','CourseController@store');
        $router->put('{id}','CourseController@update');        
    });
    
    $router->group(['middleware'=>'student'],function () use ($router){
        $router->post('','StudentController@store');
        $router->put('{id}','StudentController@update');        
        $router->get('', 'StudentController@indexPaginated');
        $router->get('all', 'StudentController@index');
        $router->get('{id}', 'StudentController@show');
        $router->delete('{id}', 'StudentController@destroy');
    });

});
