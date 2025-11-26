<?php
session_start();
require '../public/config/config.php';

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

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>connection</title>
    <link rel="stylesheet" href="../public/asset/CSS/style.css">
</head>
<body>
<form method="POST">
    <label>Email :</label>
    <input type="email" name="email" required>

    <label>Mot de passe :</label>
    <input type="password" name="password" required>

    <button type="submit">Se connecter</button>
</form>
</body>
</html>