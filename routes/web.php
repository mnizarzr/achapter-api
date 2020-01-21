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

    $router->get('/user', "UserController@index");
    $router->put('/user/edit', "UserController@edit");

    $router->get('/author', 'AuthorController@index');
    $router->get('/author/{id}', 'AuthorController@getAllBooks');
    $router->post('/author', 'AuthorController@create');
    $router->put('/author/{id}', 'AuthorController@edit');
    $router->delete('/author/{id}', 'AuthorController@delete');

    $router->get('/publisher', 'PublisherController@index');
    $router->get('/publisher/{id}', 'PublisherController@getAllBooks');
    $router->post('/publisher', 'PublisherController@create');
    $router->put('/publisher/{id}', 'PublisherController@edit');
    $router->delete('/publisher/{id}', 'PublisherController@delete');

    $router->get('/shipper', 'ShipperController@index');
    $router->post('/shipper', 'ShipperController@create');
    $router->put('/shipper/{id}', 'ShipperController@edit');
    $router->delete('/shipper/{id}', 'ShipperController@delete');

    $router->get('/genre', 'GenreController@index');
    $router->post('/genre', 'GenreController@create');
    $router->put('/genre/{id}', 'GenreController@edit');
    $router->delete('/genre/{id}', 'GenreController@delete');

    $router->get('/book', 'BookController@index');
    $router->post('/book', 'BookController@create');
    $router->get('/book/{id}', 'BookController@find');
    $router->get('/book/{name:[a-zA-Z0-9_]+}', 'BookController@findByName');
    $router->put('/book/{id}', 'BookController@edit');
    $router->delete('/book/{id}', 'BookController@delete');

    $router->get('/search/{keyword}', 'SearchController@index');

    $router->post('/order/{id}', "OrderController@create");
    $router->post('/confirm_order/{order_id}', 'OrderController@confirmOrder');
    $router->post("/pay", "PaymentController@index");

    $router->get('/feed', "FeedController@index");
    $router->get('/feed/{feedName}', "FeedController@getFeed");
    

});