<?php

/**
 * Connexion PDO partagée (singleton léger).
 * Compatible PHP 5.6+ (EasyPHP / hébergements anciens).
 */
function bee_pdo()
{
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $configPath = dirname(__DIR__) . '/config/config.php';
    if (!is_readable($configPath)) {
        throw new RuntimeException(
            'Fichier config/config.php introuvable. Copiez config/config.example.php vers config/config.php.'
        );
    }

    $config = require $configPath;
    $db     = $config['db'];

    $dsn = sprintf(
        'mysql:host=%s;dbname=%s;charset=%s',
        $db['host'],
        $db['name'],
        $db['charset']
    );

    $pdo = new PDO($dsn, $db['user'], $db['pass'], [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    return $pdo;
}
