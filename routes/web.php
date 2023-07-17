<?php

use Core\Route;

Route::get('/', 'web/HomeController@index');
Route::get('/install', 'web/HomeController@install');

// Users
Route::get('/user/{id}', 'web/UserController@show');