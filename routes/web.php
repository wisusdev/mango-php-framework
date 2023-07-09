<?php

$router = new Core\Route();

$router->add('/', ['HomeController', 'index']);
$router->add('/install', ['HomeController', 'install']);

// Auth Endpoints
$router->add('/api/login', ['UserController', 'login']);
$router->add('/api/register', ['UserController', 'register']);
$router->add('/api/password-reset', ['UserController', 'resetPassword']);

// Lists Endpoints
$router->add('/api/lists', ['ListController', 'create']);
$router->add('/api/lists', ['ListController', 'update']);
$router->add('/api/lists', ['ListController', 'add']);
$router->add('/api/lists/items', ['ListController', 'addItem']);
