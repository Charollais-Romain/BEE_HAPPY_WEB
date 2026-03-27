<?php

require_once __DIR__ . '/_common.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id < 1) {
    bee_json_error('Paramètre id invalide.');
}

$days = isset($_GET['days']) ? max(1, min(365, (int) $_GET['days'])) : 30;

try {
    $pdo = bee_pdo();
} catch (Exception $e) {
    bee_json_error('Connexion base de données impossible.', 500);
}

$st = $pdo->prepare('SELECT id FROM bee_ruches WHERE id = ?');
$st->execute([$id]);
if (!$st->fetch()) {
    bee_json_error('Ruche introuvable.', 404);
}

$sql = <<<'SQL'
SELECT
    mesure_at,
    CAST(poids_kg AS CHAR) AS poids_kg,
    CAST(temperature_c AS CHAR) AS temperature_c
FROM bee_mesures
WHERE ruche_id = ?
  AND mesure_at >= (NOW() - INTERVAL ? DAY)
ORDER BY mesure_at ASC
SQL;

$st = $pdo->prepare($sql);
$st->execute([$id, $days]);
$rows = $st->fetchAll();

$labels = [];
$poids  = [];
$temps  = [];

foreach ($rows as $row) {
    $dt = new DateTimeImmutable($row['mesure_at']);
    $labels[] = $dt->format('d/m H\h');
    $poids[]  = $row['poids_kg'] !== null ? (float) $row['poids_kg'] : null;
    $temps[]  = $row['temperature_c'] !== null ? (float) $row['temperature_c'] : null;
}

$last = end($rows) ?: null;
$summary = [
    'nb_mesures' => count($rows),
    'periode_jours' => $days,
];

if ($last) {
    $summary['dernier_poids_kg'] = $last['poids_kg'] !== null ? (float) $last['poids_kg'] : null;
    $summary['derniere_temperature_c'] = $last['temperature_c'] !== null ? (float) $last['temperature_c'] : null;
    $summary['derniere_mesure_at'] = $last['mesure_at'];
}

$poidsVals = array_filter($poids, function ($v) {
    return $v !== null;
});
if ($poidsVals !== array()) {
    $summary['poids_min_kg'] = min($poidsVals);
    $summary['poids_max_kg'] = max($poidsVals);
    $summary['poids_moyen_kg'] = round(array_sum($poidsVals) / count($poidsVals), 3);
}

bee_json_ok([
    'series' => [
        'labels' => $labels,
        'poids_kg' => $poids,
        'temperature_c' => $temps,
    ],
    'summary' => $summary,
]);
