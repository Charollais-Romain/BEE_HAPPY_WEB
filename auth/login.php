<?php
session_start();

// Infos de connexion à la base de données
$host = "135.125.103.133";
$dbname = "ruche";
$user = "ruche";
$pass = "btssnirRUCHE";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = $_POST['login'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM Ruche__utilisateur WHERE login = ?");
    $stmt->execute([$login]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && $password === $user['password']) {

        $_SESSION['user'] = $user['login'];

        // Création du token
         $token = base64_encode($user['login'] . "|" . time());

        // // Redirection vers dashboard
         header("Location: ../index.php?token=".$token);
        // exit();

        header("Location:index.php");

    } else {
        $error = "Login ou mot de passe incorrect";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Connexion</title>

<link rel="stylesheet" href="../public/assets/css/login.css">

</head>
<body>

<h1>🐝 BEE HAPPY</h1>

<div class="login-container">

    <h2>PAGE DE CONNEXION</h2>

    <form action="" method="post">

        <label>IDENTIFIANT :</label>
        <input type="text" name="login" required>

        <label>MOT DE PASSE :</label>
        <input type="password" name="password" required>

        <!-- <div class="options">
            <label>
                <input type="checkbox"> Se souvenir de moi
            </label>
            <a href="#">Mot de passe oublié</a>
        </div> -->

        <button type="submit">Connexion</button>

    </form>
    
    <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>

</div>

</body>
</html>