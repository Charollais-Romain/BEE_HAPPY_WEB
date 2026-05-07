<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Ruches Connectées</title>

    <!-- Styles -->
    <link rel="stylesheet" href="../public/assets/css/style.css">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
</head>

<body>
    <button id="logoutBtn" type="button">Déconnexion</button>
    <?php   
    require_once 'includes/auth.php';
    ?>
    <?php include '../includes/header.php'; ?>
    
    <div style="text-align: center;">
    <h2>Dashboard Ruche Connectée</h2>
    </div>
    
    <div class="dashboard">
    <!-- Carte -->
    <div id="map"></div>
    <!-- Graphique -->
    <div class="chart-container">
        <canvas id="temperatureChart"></canvas>
    </div>
    </div>
   

    <!-- Librairies -->
    <script src="/socket.io/socket.io.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <!-- Scripts JS -->
    <script src="../public/assets/js/map.js"></script>
    <script src="../public/assets/js/map.jschart.js"></script>
    <script src="../public/assets/js/map.jssocket.js"></script>
    <script>
        document.getElementById("logoutBtn").addEventListener("click", () => {
        
            // Remove token from URL (client-side protection)
            window.location.href = "https://ruches.innovelectronique.fr/auth/login.php";
        
        });
        
        </script>
 <?php include '../includes/footer.php'; ?>
</body>
</html>