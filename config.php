<?php

declare(strict_types=1);

$env = require __DIR__ . '/env-loader.php';

$config = [
    'db' => [
        'host' => $env['DB_HOST'],
        'port' => $env['DB_PORT'],
        'name' => $env['DB_NAME'],
        'user' => $env['DB_USER'],
        'password' => $env['DB_PASSWORD'],
    ],
    'app' => [
        'name' => $env['APP_NAME'],
        'base_url' => $env['APP_BASE_URL'],
    ],
];

function getDbConnection(): mysqli
{
    global $config;

    if (!extension_loaded('mysqli')) {
        throw new RuntimeException('The mysqli extension is required for MySQL/MariaDB support.');
    }

    $connection = new mysqli(
        $config['db']['host'],
        $config['db']['user'],
        $config['db']['password'],
        $config['db']['name'],
        (int) $config['db']['port']
    );

    if ($connection->connect_error) {
        throw new RuntimeException('Database connection failed: ' . $connection->connect_error);
    }

    $connection->set_charset('utf8mb4');

    return $connection;
}
