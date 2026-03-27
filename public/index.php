<?php
$pageTitle = 'Carte des ruchers';
$basePath  = '';
require __DIR__ . '/_partials/header.php';
?>

<section class="bee-hero">
    <h1>Vos ruchers</h1>
    <p>Cliquez sur un rucher pour voir le détail et la position des ruches.</p>
</section>

<div id="map-error" class="bee-alert" style="display:none;margin-bottom:1rem;" role="alert"></div>

<div class="bee-card bee-card-pad">
    <div class="bee-map-wrap">
        <div id="map" class="bee-leaflet-tiles" aria-label="Carte des ruchers"></div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="js/dashboard-map.js"></script>

<?php require __DIR__ . '/_partials/footer.php'; ?>
