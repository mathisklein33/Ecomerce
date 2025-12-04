<?php


require 'public/config/config.php'; // <-- assure-toi que $conn est bien ici

$errorMessage = "";

// Si déjà connecté → redirection
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// Si formulaire soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $firstname = trim($_POST['firstname']);
    $surname   = trim($_POST['surname']);
    $adresse   = trim($_POST['adresse']);
    $email     = trim($_POST['email']);
    $password  = $_POST['password'];

    // Vérifier si l'email existe déjà (y compris le rôle pour reconnaître un invité)
    $stmt = $conn->prepare("SELECT iduser, role_idrole FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $exists = $stmt->get_result();

    if ($exists->num_rows > 0) {
        $row = $exists->fetch_assoc();
        // Si c'est un compte 'invité' (role_idrole = 3), on permet de finaliser le compte
        if ((int)$row['role_idrole'] === 3) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $upd = $conn->prepare("UPDATE user SET password = ?, role_idrole = 2, firstname = ?, surname = ?, adresse = ? WHERE iduser = ?");
            $upd->bind_param("ssssi", $hashedPassword, $firstname, $surname, $adresse, $row['iduser']);
            if ($upd->execute()) {
                // Connexion auto après activation
                $_SESSION['user_id'] = $row['iduser'];
                $_SESSION['email']   = $email;
                $_SESSION['role']    = 2;

                header("Location: index.php");
                exit;
            } else {
                $errorMessage = "Erreur lors de l’activation du compte invité.";
            }
        } else {
            $errorMessage = "Cet email est déjà utilisé.";
        }
    } else {

        // HASH DU MOT DE PASSE
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // INSERTION UTILISATEUR
        $insert = $conn->prepare("
            INSERT INTO user (firstname, surname, adresse, email, password, role_idrole)
            VALUES (?, ?, ?, ?, ?, 2)
        ");
        $insert->bind_param("sssss", $firstname, $surname, $adresse, $email, $hashedPassword);

        if ($insert->execute()) {

            // Connexion auto après inscription
            $_SESSION['user_id'] = $insert->insert_id;
            $_SESSION['email']    = $email;
            $_SESSION['role']     = 2;

            header("Location: index.php");
            exit;
        } else {
            $errorMessage = "Erreur lors de la création du compte.";
        }
    }
}

?>

<!-- AFFICHAGE DU FORMULAIRE -->

<div class="register-body">

    <div class="register-container">
        <h2 class="register-title">Créer un compte</h2>

        <?php if (!empty($errorMessage)) : ?>
            <div style="color:red; text-align:center; margin-bottom:10px;">
                <?= htmlspecialchars($errorMessage) ?>
            </div>
        <?php endif; ?>

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
