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

if (!$user) {
    die("Utilisateur introuvable.");
}

// Mise à jour de la session rôle
$_SESSION['role'] = $user['role_idrole'];
?>

<section class="profile-section d-flex justify-content-center align-items-start pt-5 border">
    <div class="profile-card p-4 text-center">

        <!-- Avatar dynamique avec firstname + surname -->
        <div>
            <img src="https://ui-avatars.com/api/?name=<?= urlencode($user['firstname'] . ' ' . $user['surname']) ?>&background=0D6EFD&color=fff&size=120" alt="avatar">
        </div>

        <h2 class="fw-semibold"><?= htmlspecialchars($user['firstname'] . ' ' . $user['surname']) ?></h2>

<p class="email"><?= htmlspecialchars($user['email']) ?></p>

<p>Adresse : <?= htmlspecialchars($user['adresse']) ?></p>

<button class="btn btn-outline-dark mb-4">Modifier information</button>

<div class="order-history-title p-2">
    Historique de commande
</div>

<div class="order-history-box mt-3 p-4">
    Aucune commande
</div>

<a href="http://localhost/savouinos/public/includes/deconnexion.php">
    <button type="button" class="logout-btn mt-4">Déconnexion</button>
</a>

        <?php if ($_SESSION['role'] == 1): ?>
            <a href="http://localhost/savouinos/?page=admin/produit" class="admin-btn mt-2">Accès Admin</a>
        <?php endif; ?>

</div>
</section>


