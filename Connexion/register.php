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
    $email = trim($_POST["email"]);
    $telephone = trim($_POST["telephone"]);
    $password = $_POST["password"];

    if (empty($login) || empty($email) || empty($telephone) || empty($password)) {
        $message = "Tous les champs sont requis.";
    }

    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Adresse email invalide.";
    }

    elseif (strlen($password) < 6) {
        $message = "Le mot de passe doit contenir au moins 6 caractères.";
    }

    else {

        // Check if login or email already exists
        $check = $conn->prepare("SELECT id_user FROM Ruche__utilisateur WHERE email = ? OR login = ?");
        $check->bind_param("ss", $email, $login);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            $message = "Un compte avec cet email ou ce login existe déjà.";
        }

        else {

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Default role
            $role = "user";

            $stmt = $conn->prepare("INSERT INTO Ruche__utilisateur (login, email, telephone, password, role) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $login, $email, $telephone, $hashed_password, $role);

            if ($stmt->execute()) {

                // Auto login after register
                $_SESSION["login"] = $login;

                // Create token for Node.js
                $token = urlencode(base64_encode($login . "|" . time()));

            // Redirect to Node dashboard
            header("Location: http://localhost:3000/dashboard?token=" . $token);
            exit();

            } else {
                $message = "Erreur lors de la création du compte.";
            }

            $stmt->close();
        }

        $check->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
<title>Créer un compte</title>
<link rel="stylesheet" href="css/register.css">

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

<label>Email</label>
<input type="email" name="email" required>

<label>Téléphone</label>
<input type="tel" name="telephone" required>

<label>Mot de passe</label>
<input type="password" name="password" required>

<button type="submit">Créer un compte</button>

</form>

</div>

</body>
</html>