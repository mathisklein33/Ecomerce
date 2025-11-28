<?php
session_start();
require 'public/config/config.php';

$errorMessage = "";


if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}



if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Sélection de l'utilisateur via son email
    $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $user = $result->fetch_assoc();

        // Vérification du mot de passe hashé
        if (password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['iduser'];
            $_SESSION['email']   = $user['email'];
            $_SESSION['role']    = $user['role_idrole'];

            header("Location: index.php");
            exit;

        } else {
            $errorMessage = "Mot de passe incorrect.";
        }

    } else {
        $errorMessage = "Cet email n'existe pas.";
    }
}
?>

<div class="register-body">

<div class="register-container">
    <h2 class="register-title">Créer un compte</h2>

    <form method="POST" class="register-form">

        <label class="register-label">Prénom :</label>
        <input type="text" name="firstname" class="register-input" required>

        <label class="register-label">Nom :</label>
        <input type="text" name="surname" class="register-input" required>

        <label class="register-label">Adresse :</label>
        <input type="text" name="adresse" class="register-input" required>

        <label class="register-label">Email :</label>
        <input type="email" name="email" class="register-input" required>

        <label class="register-label">Mot de passe :</label>
        <input type="password" name="password" class="register-input" required>

        <button type="submit" class="register-button">S'inscrire</button>
    </form>
</div>

</div>

