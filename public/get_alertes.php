<?php

 include '../includes/db.php';

$sql = "
SELECT 
    id_alerte,
    nom,
    criticite,
    message,
    date_heure,
    id_capteur
FROM Ruche__alertes
ORDER BY date_heure DESC
";

$result = $conn->query($sql);

$alertes = [];

while($row = $result->fetch_assoc()){

    $alertes[] = $row;
}

echo json_encode($alertes);
?>