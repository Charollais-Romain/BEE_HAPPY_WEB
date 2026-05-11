<?php 
include("../includes/db.php");
include("../includes/header.php");

$ruche_id = $_GET['id'] ?? 1;

// Historique
$stmt = $pdo->prepare("
    SELECT * FROM Ruche__capteur
    WHERE id_capteur = ? 
    ORDER BY date_heure DESC 
    LIMIT 50
");
$stmt->execute([$ruche_id]);
$data = array_reverse($stmt->fetchAll());

$dates = [];
$temps = [];
$hums = [];

foreach ($data as $row) {
    $dates[] = $row['date_heure'];
    $temps[] = $row['temperature'];
    $mass[] = $row['masse'];
}
?>

<h1>Ruche <?= $ruche_id ?></h1>

<div class="card">
    <p>Température : <span id="temp">--</span> °C</p>
    <p>Masse : <span id="masse">--</span> %</p>
</div>

<canvas id="chart"></canvas>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const ctx = document.getElementById('chart').getContext('2d');

window.chart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode($dates) ?>,
        datasets: [
            {
                label: 'Température',
                data: <?= json_encode($temps) ?>
            },
        ]
    }
});
</script>