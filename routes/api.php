<?php

use Core\Route;

// Auth Endpoints
Route::post('/api/login', 'api/v1/AuthController@login');
Route::post('/api/register', 'api/v1/AuthController@register');
Route::post('/api/password-reset', 'api/v1/AuthController@resetPassword');

// Lists Endpoints
Route::post('/api/lists', 'api/v1/ListController@create');
Route::put('/api/lists/{id}', 'api/v1/ListController@update');
Route::post('/api/lists', 'api/v1/ListController@add');

Route::get('/api/user/{id}', 'api/v1/UserController@show');