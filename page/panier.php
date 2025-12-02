<?php

// --- INITIALISATION DU PANIER ---
$panier = $_SESSION['panier'] ?? [];
$subtotal = 0;

// --- GESTION DES ACTIONS ---
$action = $_GET['action'] ?? null;
$id_produit = $_GET['id'] ?? null;

if ($action && $id_produit !== null) {

    // On vérifie que le produit existe dans le panier
    if (isset($panier[$id_produit])) {

        // Récupérer le stock réel du produit dans la BDD
        require 'public/config/config.php'; // si pas déjà inclus
        $stmt = $conn->prepare("SELECT stock FROM produit WHERE idproduit = ?");
        $stmt->bind_param("i", $id_produit);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $stock_dispo = $data['stock'];

        // Message d'erreur si besoin
        $error = null;

        switch ($action) {

            case 'increase':
                // Vérifier si on dépasse le stock
                if ($panier[$id_produit]['quantite'] + 1 > $stock_dispo) {
                    $error = "Stock insuffisant pour ce produit !";
                } else {
                    $panier[$id_produit]['quantite']++;
                }
                break;

            case 'decrease':
                $panier[$id_produit]['quantite']--;
                if ($panier[$id_produit]['quantite'] <= 0) {
                    unset($panier[$id_produit]);
                }
                break;

            case 'remove':
                unset($panier[$id_produit]);
                break;
        }

        // Mise à jour session uniquement si OK
        $_SESSION['panier'] = $panier;

        // Si erreur → on la stocke dans la session
        if ($error) {
            $_SESSION['error_stock'] = $error;
        }

        // Redirection
        header('Location: ?page=panier');
        exit();
    }
}


if ($action && $id_produit !== null) {
    // On vérifie que le produit existe dans le panier
    if (isset($panier[$id_produit])) {
        switch ($action) {
            case 'increase':
                $panier[$id_produit]['quantite']++;
                break;

            case 'decrease':
                $panier[$id_produit]['quantite']--;
                if ($panier[$id_produit]['quantite'] <= 0) {
                    unset($panier[$id_produit]);
                }
                break;

            case 'remove':
                unset($panier[$id_produit]);
                break;
        }

        // Mise à jour de la session
        $_SESSION['panier'] = $panier;

        // Redirection pour éviter la répétition GET
        header('Location: ?page=panier');
        exit();
    }
}

?>

<div class="container py-5">
    <h2 class="mb-4 fw-bold">Votre panier</h2>
    <div class="row">

        <div class="col-lg-8">
            <?php if (empty($panier)): ?>
                <p>Votre panier est vide.</p>
            <?php else: ?>
                <?php foreach ($panier as $id => $produit):
                    $lineTotal = $produit['prix'] * $produit['quantite'];
                    $subtotal += $lineTotal;
                    echo $produit["image"];
                    ?>
                    <div class="cart-item d-flex align-items-center p-3 mb-4 shadow-sm rounded-4">
                        <img src="public/asset/img/<?= $produit['image'] ?>" class="item-img rounded">
                        <div class="ms-3 flex-grow-1">
                            <h5 class="fw-semibold mb-1"><?= $produit['name'] ?></h5>
                            <p class="text-muted small"><?= $produit['description'] ?></p>
                            <div class="d-flex align-items-center quantity-box">
                                <a href="?page=panier&action=decrease&id=<?= $id ?>" class="btn btn-light">−</a>
                                <p class="mx-3"><?= $produit['quantite'] ?></p>
                                <a href="?page=panier&action=increase&id=<?= $id ?>" class="btn btn-light">+</a>
                            </div>
                        </div>
                        <div class="text-end">
                            <p class="item-price"><?= number_format($lineTotal, 2) ?>€</p>
                            <small class="text-muted"><?= number_format($produit['prix'], 2) ?>€ pièce</small>
                            <div class="mt-2">
                                <a href="?page=panier&action=remove&id=<?= $id ?>" class="text-danger">
                                    <i class="bi bi-trash trash-icon"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="col-lg-4">
            <?php
            $tax = $subtotal * 0.08;
            $total = $subtotal + $tax;
            ?>
            <div class="order-box shadow-sm p-4 rounded-4">
                <h2 class="fw-bold mb-4">Résumé de la commande</h2>
                <div class="d-flex justify-content-between mb-2">
                    <p>Total (<?= count($panier) ?> article<?= count($panier) > 1 ? 's' : '' ?>)</p>
                    <p><?= number_format($subtotal, 2) ?>€</p>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <p>Livraison</p>
                    <p>GRATUIT</p>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <p>Taxe (8%)</p>
                    <p><?= number_format($tax, 2) ?>€</p>
                </div>
                <hr>
                <div class="d-flex justify-content-between fw-bold mb-4">
                    <p>Total</p>
                    <p class="text-primary"><?= number_format($total, 2) ?>€</p>
                </div>
                <a href="?page=commande" class="btn btn-dark w-100 mb-3">Finaliser la commande</a>
                <a href="?page=catalogue" class="btn btn-outline-secondary w-100">Continuer mes achats</a>
            </div>
        </div>

    </div>
</div>
