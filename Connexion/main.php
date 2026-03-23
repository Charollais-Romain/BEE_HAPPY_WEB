<?php
header("Content-Type: application/json");

//Connexion a la BDD
$host = "localhost";
$dbname = "morganl_b";
$user = "morganl";
$pass = "morganl";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die(json_encode(["error" => "Connexion échouée"]));
}


$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["error" => "Aucune donnée reçue"]);
    exit();
}


$poids = $data["poids"] ?? null;
$temp = $data["temp"] ?? null;
$lat = $data["lat"] ?? null;
$lng = $data["lng"] ?? null;
$id_capteur = $data["id_capteur"] ?? null;

// Basic validation
if ($poids === null || $temp === null || $lat === null || $lng === null || $id_capteur === null) {
    echo json_encode(["error" => "Données incomplètes"]);
    exit();
}

//Insert dans la BDD
$stmt = $conn->prepare("
    INSERT INTO Ruche__mesure (poids, temp, lat, lng, date_heure, id_capteur)
    VALUES (?, ?, ?, ?, NOW(), ?)
");

$stmt->bind_param("ddddi", $poids, $temp, $lat, $lng, $id_capteur);

if ($stmt->execute()) {
    echo json_encode(["success" => "Donnée enregistrée"]);
} else {
    echo json_encode(["error" => "Erreur insertion"]);
}

$stmt->close();
$conn->close();
?>