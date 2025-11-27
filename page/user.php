<?php
include '../public/includes/header.php';
?>


<section class="profile-section d-flex justify-content-center align-items-start pt-5">
    <div class="profile-card p-4 text-center">
        <img src="https://ui-avatars.com/api/?name=<?= urlencode($user['username']) ?>&background=0D6EFD&color=fff&size=120"
        <h2 class="fw-semibold">admin</h2>
        <p class="email">admin@gmail.com</p>

        <p>adresse : 7 rue du-valle netville</p>
        <p>moyen de paiement : paypal</p>

        <button class="btn btn-outline-dark mb-4">modifier information</button>

        <div class="order-history-title p-2">
            historique de commande
        </div>

        <div class="order-history-box mt-3 p-4">
            aucune commande
        </div>

        <button class="logout-btn mt-4">Déconnexion</button>
        <button class="admin-btn mt-2">Accès Admin</button>
    </div>
</section>

<?php
include '../public/includes/footer.php';
?>

