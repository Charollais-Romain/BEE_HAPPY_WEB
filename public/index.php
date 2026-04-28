<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Ruches Connectées</title>

    <!-- Styles -->
    <link rel="stylesheet" href="css/style.css">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
</head>

<body>
    <?php   
    require_once 'auth.php';
    ?>
    <?php include 'header.php'; ?>

    <button id="logoutBtn" type="button">Déconnexion</button>
    <h2>Dashboard Ruche Connectée</h2>

    <!-- Carte -->
    <div id="map"></div>

    <!-- Graphique -->
    <div class="chart-container">
        <canvas id="temperatureChart"></canvas>
    </div>

    <?php include 'footer.php'; ?>

    <!-- Librairies -->
    <script src="/socket.io/socket.io.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <!-- Scripts JS -->
    <script src="js/map.js"></script>
    <script src="js/chart.js"></script>
    <script src="js/socket.js"></script>
    <script>
        document.getElementById("logoutBtn").addEventListener("click", () => {
        
            // Remove token from URL (client-side protection)
            window.location.href = "http://10.187.52.4/~morganl/ProjetRuche/logout.php";
        
        });
        </script>

</body>
</html>