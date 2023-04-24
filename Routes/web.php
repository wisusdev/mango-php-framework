<?php

$route = new Core\Route();

$route->get("/", ["HomeController", "index"]);

$route->get("/user/{id}", ["HomeController", "show"]);