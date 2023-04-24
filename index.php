<?php

require "vendor/autoload.php";

use Core\Route;
use Core\Whoops;

Whoops::run();

$requestURI = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$requestMethod = $_SERVER['REQUEST_METHOD'];

try {
	Route::load('Routes/web.php')->call($requestURI, $requestMethod);
} catch (Exception $exception) {
	die($exception->getMessage());
}
