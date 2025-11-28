<?php
session_start();
require 'public/config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = $_POST['email'];
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

            // Stockage des infos utilisateurs
            $_SESSION['user_id'] = $user['iduser'];
            $_SESSION['email']   = $user['email'];
            $_SESSION['role']    = $user['role_idrole']; // Optionnel

            header("Location: index.php");
            exit;

        } else {
            echo "Mot de passe incorrect.";
        }

    } else {
        echo "Cet email n'existe pas.";
    }
}
?>

<div class="login-body">

<div class="login-container">

    <h2 class="login-title">Se connecter</h2>

    <form method="POST" class="login-form">

        <label class="label">Email :</label>
        <input type="email" name="email" class="input-field" required>

        <label class="label">Mot de passe :</label>
        <input type="password" name="password" class="input-field" required>

        <button type="submit" class="btn-login">Se connecter</button>
    </form>

</div>
</div>