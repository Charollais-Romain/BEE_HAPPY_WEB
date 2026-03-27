<?php

require_once __DIR__ . '/_common.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id < 1) {
    bee_json_error('Paramètre id invalide.');
}

try {
    $pdo = bee_pdo();
} catch (Exception $e) {
    bee_json_error('Connexion base de données impossible.', 500);
}

$st = $pdo->prepare(
    'SELECT id, nom, CAST(latitude AS CHAR) AS lat, CAST(longitude AS CHAR) AS lng, description
     FROM bee_ruchers WHERE id = ?'
);
$st->execute([$id]);
$rucher = $st->fetch();

if (!$rucher) {
    bee_json_error('Rucher introuvable.', 404);
}

$rucher['id'] = (int) $rucher['id'];

$st2 = $pdo->prepare(
    'SELECT id, nom, CAST(latitude AS CHAR) AS lat, CAST(longitude AS CHAR) AS lng
     FROM bee_ruches WHERE rucher_id = ? ORDER BY nom'
);
$st2->execute([$id]);
$ruches = $st2->fetchAll();

foreach ($ruches as &$r) {
    $r['id'] = (int) $r['id'];
}
unset($r);

bee_json_ok(['rucher' => $rucher, 'ruches' => $ruches]);
