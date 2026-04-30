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
    <button id="logoutBtn" type="button">Déconnexion</button>
    <?php   
    require_once 'auth.php';
    ?>
    <?php include 'header.php'; ?>
    
    <div style="text-align: center;">
    <h2>Dashboard Ruche Connectée</h2>
    </div>
    
    <!-- Carte -->
    <div id="map"></div>

    <!-- Graphique -->
    <div class="chart-container">
        <canvas id="temperatureChart"></canvas>
    </div>

   

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
 <?php include 'footer.php'; ?>
</body>
</html>