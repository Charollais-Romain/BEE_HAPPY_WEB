#!/usr/bin/env php
<?php

/**
 * Génère des mesures de démo (poids + température) sur ~45 jours.
 * Usage : php scripts/seed_mesures.php
 */

require_once dirname(__DIR__) . '/includes/db.php';

$pdo = bee_pdo();

$ids = $pdo->query('SELECT id FROM bee_ruches')->fetchAll(PDO::FETCH_COLUMN);
if (!$ids) {
    fwrite(STDERR, "Aucune ruche trouvée. Importez d'abord sql/schema.sql\n");
    exit(1);
}

$pdo->exec('DELETE FROM bee_mesures');

$stmt = $pdo->prepare(
    'INSERT INTO bee_mesures (ruche_id, poids_kg, temperature_c, mesure_at) VALUES (?, ?, ?, ?)'
);

$now = new DateTimeImmutable('now', new DateTimeZone('UTC'));
$count = 0;

foreach ($ids as $rucheId) {
    $basePoids = 18.0 + (crc32((string) $rucheId) % 800) / 100;
    for ($d = 45; $d >= 0; $d--) {
        for ($h = 0; $h < 24; $h += 6) {
            $t = $now->modify(sprintf('-%d days', $d))->setTime($h, mt_rand(0, 59));
            $wave = sin(($d + $h / 24) / 8) * 1.2;
            $noise = (mt_rand(-50, 50) / 100);
            $poids = round($basePoids + $wave + $noise + ($d * -0.02), 3);
            $temp  = round(32 + sin($h / 24 * M_PI) * 4 + mt_rand(-20, 20) / 10, 2);

            $stmt->execute([(int) $rucheId, $poids, $temp, $t->format('Y-m-d H:i:s')]);
            $count++;
        }
    }
}

echo "Inséré : {$count} mesures pour " . count($ids) . " ruches.\n";
