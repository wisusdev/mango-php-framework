<?php

require "vendor/autoload.php";
require "core/bootstrap.php";
require "routes/web.php";
require "routes/api.php";

use Core\Route;
use Core\Whoops;

Whoops::run();

try {
	Route::handle($_SERVER['REQUEST_URI']);
} catch (Exception $exception) {
	die($exception->getMessage());
}
