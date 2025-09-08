<?php
declare(strict_types=1);

/**
 * @param string $name
 *
 * @return string|null
 */
function read_secret(string $name): ?string
{
    $name = strtoupper($name);
    if ($val = getenv($name)) {
        return trim($val);
    }
    if ($val = getenv($name . '_FILE')) {
        return trim(file_get_contents($val));
    }
    return null;
}

$_CONFIG = [
    'hostname' => read_secret('DB_HOST') ?? 'localhost',
    'username' => read_secret('DB_USER') ?? 'YOURDBUSERNAME',
    'password' => read_secret('DB_PASS') ?? 'YOURDBPASSWORD',
    'database' => read_secret('DB_NAME') ?? 'YOURDBDATABASE',
    'driver'   => 'mysqli',
    'code'     => read_secret('APP_KEY') ?? 'blahblah',
];
