<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location:https://ruches.innovelectronique.fr/auth/login.php");
    exit();
}
?>