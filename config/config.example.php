<?php
/**
 * Copiez ce fichier vers config.php et ajustez les identifiants MySQL.
 * cp config/config.example.php config/config.php
 */
return [
    'db' => [
        'host'    => getenv('BEE_DB_HOST') ?: 'localhost',
        'name'    => getenv('BEE_DB_NAME') ?: 'beehappy',
        'user'    => getenv('BEE_DB_USER') ?: 'root',
        'pass'    => getenv('BEE_DB_PASS') ?: '',
        'charset' => 'utf8mb4',
    ],
];
