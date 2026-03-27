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

$sql = <<<'SQL'
SELECT
    u.id,
    u.nom,
    u.rucher_id,
    CAST(u.latitude AS CHAR) AS lat,
    CAST(u.longitude AS CHAR) AS lng,
    r.nom AS rucher_nom
FROM bee_ruches u
JOIN bee_ruchers r ON r.id = u.rucher_id
WHERE u.id = ?
SQL;

$st = $pdo->prepare($sql);
$st->execute([$id]);
$ruche = $st->fetch();

if (!$ruche) {
    bee_json_error('Ruche introuvable.', 404);
}

$ruche['id']        = (int) $ruche['id'];
$ruche['rucher_id'] = (int) $ruche['rucher_id'];

bee_json_ok(['ruche' => $ruche]);
