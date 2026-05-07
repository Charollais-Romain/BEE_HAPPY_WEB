<?php
session_start();

$host = "localhost";
$dbname = "morganl_b";
$user = "morganl";
$pass = "morganl";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $login = trim($_POST["login"]);
    $password = $_POST["password"];
    $prenom = $_POST["prenom"];
    $nom = $_POST["nom"];
    $email = trim($_POST["email"]);
    $telephone = trim($_POST["telephone"]);
    $adresse = $_POST["adresse"];
    

    if (empty($login) || empty($email) || empty($telephone) || empty($password) || empty($prenom) || empty($nom) || empty($adresse)) {
        $message = "Tous les champs sont requis.";
    }

    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Adresse email invalide.";
    }

    elseif (strlen($password) < 6) {
        $message = "Le mot de passe doit contenir au moins 6 caracteres.";
    }

    else {

        // Checks if login or email already exists
        $check = $conn->prepare("SELECT id_user FROM Ruche__utilisateur WHERE email = ? OR login = ?");
        $check->bind_param("ss", $email, $login);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $message = "Un compte avec cet email ou ce login existe deja.";
        }

        else {

            //$hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Default role
            $role = "user";

            $stmt = $conn->prepare("INSERT INTO Ruche__utilisateur (prenom, nom, login, password, email, telephone, adresse, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssss", $prenom, $nom, $login, $password, $email, $telephone, $adresse, $role);

            if ($stmt->execute()) {

                // Auto login after register
                $_SESSION["login"] = $login;


                // Creer un token pour Node.js
                $token = urlencode(base64_encode($login . "|" . time()));


            // Redirections vers dashboard Node 
            header("Location:Location:ruches.innovelectronique.fr/index.php?token=" . $token);
            exit();

            } else {
                $message = "Erreur lors de la creation du compte.";
            }

            $stmt->close();
        }

        $check->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<meta charset="UTF-8">
<html>
<head>
<title>Créer un compte</title>
<link rel="stylesheet" href="../public/assets/css/register.css">
</head>


<body>

<div class="container">
<h2>Créer un compte</h2>

<?php if(!empty($message)) { ?>
<p class="error"><?php echo $message; ?></p>
<?php } ?>

<form method="POST">

<label>Login</label>
<input type="text" name="login" required>

<label>Mot de passe</label>
<input type="text" name="password" required>

<label>Prénom</label>
<input type="text" name="prenom" required>

<label>Nom</label>
<input type="text" name="nom" required>

<label>Adresse</label>
<input type="text" name="adresse" required>

<label>Email</label>
<input type="text" name="email" required>

<label>Telephone</label>
<input type="text" name="telephone" required>


<button type="submit">Creer un compte</button>

</form>

</div>

</body>
</html>