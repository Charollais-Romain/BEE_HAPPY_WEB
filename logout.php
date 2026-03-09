<?php
session_start();

// Supprime toutes les variables de session
$_SESSION = [];

// Détruit la session
session_destroy();

// Redirection vers la page de login
header("Location: login.php");
exit();
?>