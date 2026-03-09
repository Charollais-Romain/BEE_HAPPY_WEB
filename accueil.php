<?php
session_start();

// Vérifie si l'utilisateur est connecté
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Accueil</title>
<style>
/* body{
    font-family: Arial, sans-serif;
    background:#f2f2f2;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
    flex-direction: column;
}

.container{
    background:white;
    padding:30px;
    border-radius:10px;
    box-shadow:0 0 10px rgba(0,0,0,0.2);
    text-align:center;
}

button{
    padding:10px 20px;
    background:#007BFF;
    border:none;
    color:white;
    cursor:pointer;
    border-radius:5px;
    margin-top:20px;
}

button:hover{
    background:#0056b3;
}*/
</style>
</head>
<body>

<div class="container">
    <h1>Bienvenue !</h1>
    <p>Vous êtes connecté avec succès.</p>
    <form action="logout.php" method="post"> 
        <button type="submit">Se déconnecter</button>
    </form>
</div>

</body>
</html>