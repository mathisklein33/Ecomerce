<?php

$error = ""; // message d’erreur affiché dans le formulaire

if (isset($_SESSION['user_id'])) {
    header("Location: http://localhost/savouinos/?page=user");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = $_POST['email'];
    $password = $_POST['password'];

    // Sélection de l'utilisateur
    $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $user = $result->fetch_assoc();

        // Vérification mot de passe
        if (password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['iduser'];
            $_SESSION['email']   = $user['email'];
            $_SESSION['role']    = $user['role_idrole'];


            header("Location: index.php");
            exit;

        } else {
            $error = "Mot de passe incorrect.";
        }

    } else {
        $error = "Cet email n'existe pas.";
    }
}
?>

<div class="login-body">

    <div class="login-container">

        <h2 class="login-title">Se connecter</h2>

        <?php if (!empty($error)) : ?>
            <div>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="login-form">

            <label class="label">Email :</label>
            <input type="email" name="email" class="input-field" style="color:black; background:white;" required>

            <label class="label">Mot de passe :</label>
            <input type="password" name="password" class="input-field" style="color:black; background:white;" required>

            <button type="submit" class="btn-login"  >Se connecter</button>
            <div class="mt-4">
                <p class="signup-text">
                    Pas encore de compte ?
                    <a class="p-2 nonetext" href="http://localhost/savouinos/?page=inscription"  >Inscription</a>
                </p>
            </div>
        </form>

    </div>
</div>

