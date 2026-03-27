<?php
$rucheId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($rucheId < 1) {
    header('Location: index.php');
    exit;
}

$pageTitle = 'Statistiques ruche';
$basePath  = '';
require __DIR__ . '/_partials/header.php';
?>

<section class="bee-hero">
    <div class="bee-breadcrumb">
        <a href="index.php">Carte</a>
        <span>/</span>
        <a href="#" id="bc-rucher-link">Rucher</a>
        <span>/</span>
        <span id="bc-ruche-name">Ruche</span>
    </div>
    <h1 id="ruche-title">Ruche</h1>
    <p>Évolution du poids et de la température sur la période choisie.</p>
</section>

<div id="stats-error" class="bee-alert" style="display:none;margin-bottom:1rem;" role="alert"></div>

<div class="bee-toolbar">
    <label for="period-select">Période</label>
    <select id="period-select" class="bee-select" aria-label="Nombre de jours">
        <option value="7">7 jours</option>
        <option value="30" selected>30 jours</option>
        <option value="90">90 jours</option>
    </select>
</div>

<div class="bee-stats-grid" id="stats-summary" aria-live="polite"></div>

<div class="bee-card bee-card-pad">
    <div class="bee-chart-wrap">
        <canvas id="chart-poids" aria-label="Graphique du poids"></canvas>
    </div>
</div>

<div class="bee-card bee-card-pad" style="margin-top:1rem;">
    <div class="bee-chart-wrap">
        <canvas id="chart-temp" aria-label="Graphique de la température"></canvas>
    </div>
</div>

<script>
    window.BEE_RUCHE_ID = <?= (int) $rucheId ?>;
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script src="js/ruche-stats.js"></script>

<?php require __DIR__ . '/_partials/footer.php'; ?>
