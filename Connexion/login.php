<?php
session_start();

// Infos de connexion à la base de données
$host = "10.187.52.4";
$dbname = "morganl_b";
$user = "morganl";
$pass = "morganl";

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

        // Redirection vers Node.js
        header("Location: http://localhost:3000/dashboard?token=".$token);
        exit();

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

<link rel="stylesheet" href="css/login.css">

</head>
<body>

<div class="login-box">
<h2>Connexion</h2>
<form action="" method="post">
    <input type="text" name="login" placeholder="Login" required>
    <input type="password" name="password" placeholder="Mot de passe" required>
    <button type="submit">Se connecter</button>
</form>

<?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>

</div>

</body>
</html>