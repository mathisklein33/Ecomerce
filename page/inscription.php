<?php
session_start();
require '../public/config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $firstname = $_POST['firstname'];
    $surname   = $_POST['surname'];
    $adresse   = $_POST['adresse'];
    $email     = $_POST['email'];
    $password  = $_POST['password'];

    // Vérifier si l'email existe déjà
    $check = $conn->prepare("SELECT email FROM user WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $res = $check->get_result();

    if ($res->num_rows > 0) {
        echo "Cet email est déjà utilisé.";
    } else {

        // Hash du mot de passe
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Rôle forcé : 1 = utilisateur simple
        $role = 1;

        // Insert
        $stmt = $conn->prepare("
            INSERT INTO user (firstname, surname, adresse, email, password, role_idrole) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param("sssssi",
            $firstname,
            $surname,
            $adresse,
            $email,
            $hashed_password,
            $role
        );

        if ($stmt->execute()) {

            echo "Compte créé avec succès !";

            // Auto-login
            $_SESSION['user_id'] = $stmt->insert_id;
            $_SESSION['email']   = $email;
            $_SESSION['role']    = 1;  // On stocke aussi le rôle en session 👍

            header("Location: index.php");
            exit;

        } else {
            echo "Erreur pendant l'inscription.";
        }
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Créer un compte</title>
    <link rel="stylesheet" href="../public/asset/CSS/style.css">
</head>
<body>

<h2>Créer un compte</h2>

<form method="POST">
    <label>Prénom :</label>
    <input type="text" name="firstname" required>

    <label>Nom :</label>
    <input type="text" name="surname" required>

    <label>Adresse :</label>
    <input type="text" name="adresse" required>

    <label>Email :</label>
    <input type="email" name="email" required>

    <label>Mot de passe :</label>
    <input type="password" name="password" required>

    <button type="submit">S'inscrire</button>
</form>

</body>
</html>
