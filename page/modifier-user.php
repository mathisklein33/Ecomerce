<?php



// Vérification connexion
if (!$conn) {
    die("Erreur MySQL : " . mysqli_connect_error());
}

// ------------------------------------------------------
// RÉCUPÉRATION DES CLÉS COMPOSITES (GET)
// ------------------------------------------------------
if (
        !isset($_GET['iduser']) || empty($_GET['iduser']) ||
        !isset($_GET['email'])  || empty($_GET['email'])  ||
        !isset($_GET['role'])   || empty($_GET['role'])
) {
    die("Identifiant utilisateur incomplet");
}

$id      = (int) $_GET['iduser'];   // iduser (clé)
$emailPK = $_GET['email'];          // email d'origine (clé)
$rolePK  = (int) $_GET['role'];     // role_idrole (clé)

// ------------------------------------------------------
// RÉCUPÉRATION DE L'UTILISATEUR
// ------------------------------------------------------
$sql = "SELECT * FROM user WHERE iduser = ? AND email = ? AND role_idrole = ?";
$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    die("Erreur préparation SQL : " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, "isi", $id, $emailPK, $rolePK);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result) {
    die("Erreur exécution SQL : " . mysqli_error($conn));
}

if (mysqli_num_rows($result) === 0) {
    die("Utilisateur introuvable");
}

$user = mysqli_fetch_assoc($result);

// ------------------------------------------------------
// MISE À JOUR (POST)
// ------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $firstname = $_POST['firstname'] ?? '';
    $surname   = $_POST['surname']   ?? '';
    $emailNew  = $_POST['email']     ?? '';
    $adresse   = $_POST['adresse']   ?? '';


    if (empty($firstname) || empty($surname) || empty($emailNew)) {
        die("Merci de remplir les champs obligatoires.");
    }

    $update = "
        UPDATE user 
        SET firstname = ?, surname = ?, email = ?, adresse = ?
        WHERE iduser = ? AND email = ? AND role_idrole = ?
    ";

    $stmt2 = mysqli_prepare($conn, $update);

    if (!$stmt2) {
        die("Erreur préparation UPDATE : " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param(
            $stmt2,
            "ssssisi",
            $firstname,
            $surname,
            $emailNew,
            $adresse,
            $id,        // iduser (clé)
            $emailPK,   // email d'origine (clé)
            $rolePK     // role_idrole (clé)
    );

    mysqli_stmt_execute($stmt2);

    if (mysqli_errno($conn)) {
        die("Erreur UPDATE : " . mysqli_error($conn));
    }

    // Ici : déconnexion + redirection vers la page de connexion
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $_SESSION = [];
    session_destroy();


    header("Location: http://localhost/savouinos/?page=login&msg=relogin");
    exit;

}

?>
<section class="bg-light">

    <div class="container mt-5">
        <div class="card shadow mx-auto" style="max-width: 600px;">
            <div class="card-body">

                <h3 class="text-center mb-4">Modifier l'utilisateur</h3>

                <!-- 🔻 Message d'avertissement -->
                <div class="alert alert-warning">
                    Toute modification de vos informations entraînera votre déconnexion
                    et vous devrez vous reconnecter.
                </div>

                <form method="POST">

                    <div class="mb-3">
                        <label class="form-label">Prénom</label>
                        <input type="text" name="firstname" class="form-control"
                               value="<?= htmlspecialchars($user['firstname']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nom</label>
                        <input type="text" name="surname" class="form-control"
                               value="<?= htmlspecialchars($user['surname']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control"
                               value="<?= htmlspecialchars($user['email']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Adresse</label>
                        <input type="text" name="adresse" class="form-control"
                               value="<?= htmlspecialchars($user['adresse']) ?>">
                    </div>

                    <button class="btn btncolor w-100">Enregistrer</button>

                   <a href="index.php">
                       <button class="mt-3 btn btncolor w-100">modifier mot de passe</button>
                   </a>


                </form>

            </div>
        </div>
    </div>

</section>
