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

/**
 * Users, Consumers e Sellers
 */
$router->group(['prefix' => 'users'], function () use ($router) {
    $router->get('/', 'UserController@list');
    $router->get('/{user_id}', 'UserController@load');
    $router->post('/', 'UserController@add');
    $router->put('/{id}', 'CongregacaoController@editar');

    $router->group(['prefix' => '/consumers'], function () use ($router) {
        $router->post('/', 'ConsumerController@add');
    });

    $router->group(['prefix' => '/sellers'], function () use ($router) {
        $router->post('/', 'SellerController@add');
    });
});

/**
 * Transactions
 */
$router->group(['prefix' => 'transactions'], function () use ($router) {
    $router->get('/{transaction_id}', 'TransactionController@load');
    $router->post('/', 'TransactionController@add');
});