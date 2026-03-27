<?php
$rucherId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($rucherId < 1) {
    header('Location: index.php');
    exit;
}

$pageTitle = 'Rucher';
$basePath  = '';
require __DIR__ . '/_partials/header.php';
?>

<section class="bee-hero">
    <div class="bee-breadcrumb">
        <a href="index.php">Carte</a>
        <span>/</span>
        <span id="rucher-breadcrumb-name">Rucher</span>
    </div>
    <h1 id="rucher-title">Rucher</h1>
    <p id="rucher-desc">Chargement…</p>
</section>

<div id="map-error" class="bee-alert" style="display:none;margin-bottom:1rem;" role="alert"></div>

<div class="bee-card bee-card-pad">
    <div class="bee-map-wrap">
        <div id="map-rucher" class="bee-leaflet-tiles" aria-label="Carte des ruches du rucher"></div>
    </div>
</div>

<script>
    window.BEE_RUCHER_ID = <?= (int) $rucherId ?>;
</script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="js/rucher-map.js"></script>

<?php require __DIR__ . '/_partials/footer.php'; ?>
