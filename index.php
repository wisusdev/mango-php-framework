<?php

require "vendor/autoload.php";

use Core\Route;
use Core\Whoops;

Whoops::run();

try {
	Route::load('Routes/web.php');
} catch (Exception $exception) {
	die($exception->getMessage());
}
