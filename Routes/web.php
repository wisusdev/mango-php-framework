<?php

$route = new Core\Route();

$route->add("/", ["HomeController", "index"]);
$route->add("/home", ["HomeController", "index"]);
$route->add("/user/{id}", ["HomeController", "show"]);
$route->add("/download/{downID}/{filename}", ["HomeController", "show"]);
