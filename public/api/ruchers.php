<?php

require_once __DIR__ . '/_common.php';

try {
    $pdo = bee_pdo();
} catch (Exception $e) {
    bee_json_error('Connexion base de données impossible.', 500);
}

$sql = <<<'SQL'
SELECT
    r.id,
    r.nom,
    CAST(r.latitude AS CHAR) AS lat,
    CAST(r.longitude AS CHAR) AS lng,
    r.description,
    (SELECT COUNT(*) FROM bee_ruches x WHERE x.rucher_id = r.id) AS nb_ruches
FROM bee_ruchers r
ORDER BY r.nom
SQL;

$rows = $pdo->query($sql)->fetchAll();

foreach ($rows as &$row) {
    $row['id']        = (int) $row['id'];
    $row['nb_ruches'] = (int) $row['nb_ruches'];
}
unset($row);

bee_json_ok(['ruchers' => $rows]);
