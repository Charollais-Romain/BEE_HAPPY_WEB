<?php
if (!isset($pageTitle)) {
    $pageTitle = 'Bee Happy';
}
if (!isset($basePath)) {
    $basePath = '';
}
require_once dirname(__DIR__) . '/../includes/bee_auth.php';
bee_session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?> — Bee Happy</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= htmlspecialchars($basePath, ENT_QUOTES, 'UTF-8') ?>css/bee-theme.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
</head>
<body class="bee-body">
    <div class="bee-bg-pattern" aria-hidden="true"></div>
    <header class="bee-header">
        <div class="bee-header-inner">
            <a href="<?= htmlspecialchars($basePath, ENT_QUOTES, 'UTF-8') ?>index.php" class="bee-logo">
                <span class="bee-logo-icon" aria-hidden="true"></span>
                <span>Bee Happy</span>
            </a>
            <nav class="bee-nav" aria-label="Navigation principale">
                <a href="<?= htmlspecialchars($basePath, ENT_QUOTES, 'UTF-8') ?>index.php">Carte</a>
                <?php if (bee_user_logged_in()) { ?>
                    <span class="bee-nav-secondary" style="opacity:0.9;"><?= htmlspecialchars(bee_user_login(), ENT_QUOTES, 'UTF-8') ?></span>
                    <a href="<?= htmlspecialchars($basePath, ENT_QUOTES, 'UTF-8') ?>logout.php" class="bee-nav-secondary">Déconnexion</a>
                <?php } else { ?>
                    <a href="<?= htmlspecialchars($basePath, ENT_QUOTES, 'UTF-8') ?>login.php" class="bee-nav-secondary">Connexion</a>
                <?php } ?>
            </nav>
        </div>
    </header>
    <main class="bee-main" id="main-content">
