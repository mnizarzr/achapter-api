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
$router->group(['prefix' => 'api'], function () use ($router) {
    $router->get('', function () {
        return response(["status" => "OK"], 200);
    });
    $router->post('/register', 'AuthController@register');
    $router->post('/login', 'AuthController@login');

    $router->get('/author', 'AuthorController@index');
    $router->post('/author', 'AuthorController@create');
    $router->put('/author/{id}', 'AuthorController@edit');
    $router->delete('/author/{id}', 'AuthorController@delete');

    $router->post('/book', 'BookController@index');
    $router->get('/book/{id}', 'BookController@find');
});