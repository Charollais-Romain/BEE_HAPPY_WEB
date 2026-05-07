<?php


// 🔥 DEBUG ON
ini_set('display_errors', 1);
error_reporting(E_ALL);


// ─── LOG brut ───────────────────────────────────────────────
file_put_contents("log.txt", date("Y-m-d H:i:s") . " | " . print_r($_GET, true) . "\n", FILE_APPEND);


// ─── Connexion BDD ──────────────────────────────────────────
$host   = "135.125.103.133";
$dbname = "ruche";
$user   = "ruche";
$pass   = "btssnirRUCHE";


try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    file_put_contents("log.txt", "ERREUR BDD: " . $e->getMessage() . "\n", FILE_APPEND);
    http_response_code(500);
    die("Erreur BDD");
}


// ─── Récupération paramètres ────────────────────────────────
$device = $_GET['id']   ?? null;
$data   = $_GET['data'] ?? null;


if ($device === null || $data === null) {
    file_put_contents("log.txt", "Paramètres manquants\n", FILE_APPEND);
    http_response_code(400);
    die("Paramètres manquants");
}


// ─── Vérif capteur ──────────────────────────────────────────
$stmt = $pdo->prepare("SELECT id_capteur FROM Ruche__capteur WHERE sigfox_id = ?");
$stmt->execute([$device]);
$capteur = $stmt->fetch(PDO::FETCH_ASSOC);


if (!$capteur) {
    file_put_contents("log.txt", "Capteur inconnu: $device\n", FILE_APPEND);
    http_response_code(404);
    die("Capteur inconnu");
}


$id_capteur = $capteur['id_capteur'];


// ─── Décodage HEX → octets ──────────────────────────────────
$bytes = [];
for ($i = 0; $i < strlen($data); $i += 2) {
    $bytes[] = hexdec(substr($data, $i, 2));
}


if (empty($bytes)) {
    file_put_contents("log.txt", "Erreur décodage hex: $data\n", FILE_APPEND);
    http_response_code(400);
    die("Erreur hex");
}


$type = $bytes[0];
file_put_contents("log.txt", "Type trame: 0x" . sprintf('%02X', $type) . " | bytes: " . count($bytes) . "\n", FILE_APPEND);


// ─── TRAITEMENT ─────────────────────────────────────────────


// 📦 TRAME MASSE + GPS (type 0x01, 10 octets)
if ($type === 0x01 && count($bytes) >= 10) {


    // Décodage masse (3 octets, grammes)
    $masse = ($bytes[1] << 16) | ($bytes[2] << 8) | $bytes[3];
    $masse = round($masse / 1000, 2); // grammes → kg


    // Décodage latitude (3 octets signé)
    $iLat = ($bytes[4] << 16) | ($bytes[5] << 8) | $bytes[6];
    if ($iLat > 0x7FFFFF) $iLat -= 0x1000000;
    $lat = $iLat / 10000;


    // Décodage longitude (3 octets signé)
    $iLon = ($bytes[7] << 16) | ($bytes[8] << 8) | $bytes[9];
    if ($iLon > 0x7FFFFF) $iLon -= 0x1000000;
    $lon = $iLon / 10000;


    file_put_contents("log.txt", "Masse+GPS | masse=$masse kg | lat=$lat | lon=$lon\n", FILE_APPEND);


    // Insertion mesure
    $stmt = $pdo->prepare("
        INSERT INTO Ruche__mesure (masse, lat, lng, date_heure, id_capteur)
        VALUES (?, ?, ?, NOW(), ?)
    ");
    $stmt->execute([$masse, $lat, $lon, $id_capteur]);


    // Mise à jour masse dans Ruche__ruche
    $stmt = $pdo->prepare("
        UPDATE Ruche__ruche r
        INNER JOIN Ruche__capteur c ON c.id_ruche = r.id_ruche
        SET r.masse = ?
        WHERE c.id_capteur = ?
    ");
    $stmt->execute([$masse, $id_capteur]);


    file_put_contents("log.txt", "Masse+GPS enregistrés\n", FILE_APPEND);


// 🚨 ALERTE POIDS
} elseif ($data === "4131") {


    $stmt = $pdo->prepare("
        INSERT INTO Ruche__alertes (nom, criticite, message, date_heure, id_capteur)
        VALUES (?, ?, ?, NOW(), ?)
    ");
    $stmt->execute([
        "Perte de poids",
        "HIGH",
        "Perte > 200g",
        $id_capteur
    ]);


    file_put_contents("log.txt", "Alerte poids enregistrée\n", FILE_APPEND);


// 🚨 ALERTE GPS
} elseif ($data === "4132") {


    $stmt = $pdo->prepare("
        INSERT INTO Ruche__alertes (nom, criticite, message, date_heure, id_capteur)
        VALUES (?, ?, ?, NOW(), ?)
    ");
    $stmt->execute([
        "Mouvement",
        "CRITICAL",
        "Déplacement détecté",
        $id_capteur
    ]);


    file_put_contents("log.txt", "Alerte GPS enregistrée\n", FILE_APPEND);


// ⚖️ MASSE SEULE (sans GPS)
} elseif (is_numeric(hex2bin($data))) {


    $decoded = hex2bin($data);
    $masse   = round((float)$decoded / 1000, 2);


    $stmt = $pdo->prepare("
        INSERT INTO Ruche__mesure (masse, date_heure, id_capteur)
        VALUES (?, NOW(), ?)
    ");
    $stmt->execute([$masse, $id_capteur]);


    // Mise à jour masse dans Ruche__ruche
    $stmt = $pdo->prepare("
        UPDATE Ruche__ruche r
        INNER JOIN Ruche__capteur c ON c.id_ruche = r.id_ruche
        SET r.masse = ?
        WHERE c.id_capteur = ?
    ");
    $stmt->execute([$masse, $id_capteur]);


    file_put_contents("log.txt", "Masse seule: $masse kg\n", FILE_APPEND);


// ❌ INCONNU
} else {
    $decoded = hex2bin($data);
    file_put_contents("log.txt", "Trame inconnue: $decoded\n", FILE_APPEND);
    http_response_code(400);
    die("Trame inconnue");
}


http_response_code(200);
echo "OK";



