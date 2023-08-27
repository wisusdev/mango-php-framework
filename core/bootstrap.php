<?php

use Core\Application;
use Core\Database\Connection;
use Core\Database\QueryBuilder;
use Core\Logs;

Application::bind('config', require 'Config/app.php');

try {
    Application::bind('database',
        new QueryBuilder(
            Connection::make(Application::get('config')['database']['mysql'])
        )
    );
} catch (Exception $e) {
    Logs::debug($e->getMessage());
    die($e->getMessage());
}

