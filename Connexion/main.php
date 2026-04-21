<?php
session_start();

// Protection de la page
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Ruche Connectée</title>
    <link rel="stylesheet" href="/public/css/style.css">
</head>
<body>

<h1>Dashboard - Ruche 🐝</h1>

<nav>
    <a href="main.php">Dashboard</a>
    <a href="carte.php">Carte</a>
    <a href="logout.php">Déconnexion</a>
</nav>

<!-- Graphique -->
<div style="width: 80%; margin: auto;">
    <canvas id="temperatureChart"></canvas>
</div>

<!-- Map -->
<div id="map"></div>

<!-- Socket.io -->
<script src="http://localhost:3000/socket.io/socket.io.js"></script>

<!-- Scripts -->
<script src="/public/js/chart.js"></script>
<script src="/public/js/socket.js"></script>
<script src="/public/js/maps.js"></script>

</body>
</html>
