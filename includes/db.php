<?php

/**
 * Database connection settings are supplied by the server environment.
 * Never add real credentials to this file or commit a local configuration.
 */
function requiredEnv(string $name): string
{
    $value = getenv($name);

    if ($value === false || $value === '') {
        throw new RuntimeException("Missing required environment variable: {$name}");
    }

    return $value;
}

$servername = requiredEnv('DB_HOST');
$username = requiredEnv('DB_USER');
$password = requiredEnv('DB_PASSWORD');
$dbname = requiredEnv('DB_NAME');
$port = (int) (getenv('DB_PORT') ?: 3306);

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    throw new RuntimeException('Database connection failed. Check the server database settings.');
}
