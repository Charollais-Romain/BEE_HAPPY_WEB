<?php
/**
 * Configuration locale — modifiez selon votre environnement.
 * Ne commitez pas de mots de passe de production (utilisez config.example.php comme modèle).
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
