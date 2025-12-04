<?php

require 'public/config/config.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['email'])) {
    header("Location: http://localhost/savouinos/?page=login");
    exit;
}

$email = $_SESSION['email'];

// --- Récupération de l'utilisateur ---
$sql = "SELECT iduser, firstname, surname, email, adresse, role_idrole 
        FROM user 
        WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
if (!$user) {
    header("Location: http://localhost/savouinos/?page=deconnexion");
    exit;
}

// Mise à jour de la session rôle
$_SESSION['role'] = $user['role_idrole'];
?>

<section class="profile-section d-flex justify-content-center align-items-start pt-5 border">
    <div class="profile-card p-4 text-center mb-5  ">

        <!-- Avatar dynamique avec firstname + surname -->
        <div>
            <img src="https://ui-avatars.com/api/?name=<?= urlencode($user['firstname'] . ' ' . $user['surname']) ?>&background=0D6EFD&color=fff&size=120" alt="avatar">
        </div>

        <h2 class="fw-semibold"><?= htmlspecialchars($user['firstname'] . ' ' . $user['surname']) ?></h2>

<p class="email"><?= htmlspecialchars($user['email']) ?></p>

<p>Adresse : <?= htmlspecialchars($user['adresse']) ?></p>
        <a class="btn btn-outline-dark mb-4"
           href="http://localhost/savouinos/?page=user/modifier&iduser=<?= urlencode($user['iduser']) ?>&email=<?= urlencode($user['email']) ?>&role=<?= urlencode($user['role_idrole']) ?>">
            Modifier information
        </a>




<?php

$sqlCommande = "SELECT *
        FROM commande
        WHERE user_iduser = ?";
$stmtCommande = $conn->prepare($sqlCommande);
$stmtCommande->bind_param("i", $user['iduser']);
$stmtCommande->execute();
$resultCommande = $stmtCommande->get_result();
$commande = $resultCommande->fetch_assoc();
?>

<div class="order-history-title p-2">
    <a href="http://localhost/savouinos/?page=HistoriqueCommande&iduser=<?= urlencode($user['iduser']) ?>">Historique de commande</a>
</div>





</div>

<a href="http://localhost/savouinos/public/includes/deconnexion.php">
    <button type="button" class="logout-btn mt-4">Déconnexion</button>
</a>

        <?php if ($_SESSION['role'] == 1): ?>
            <a href="http://localhost/savouinos/?page=admin/produit" class="admin-btn mt-2">Accès Admin</a>
        <?php endif; ?>

</div>
</section>


