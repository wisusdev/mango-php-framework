<?php

use Core\Application;
use Core\Database\Connection;
use Core\Database\QueryBuilder;

Application::bind('config', require 'Config/app.php');

try {
    Application::bind('database',
        new QueryBuilder(
            Connection::make(Application::get('config')['database']['mysql'])
        )
    );
} catch (Exception $e) {
    die($e->getMessage());
}

