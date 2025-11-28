<?php
session_start();
$panier = $_SESSION['panier'] ?? [];
$subtotal = 0; // Initialisation du sous-total

// --- GESTION DES ACTIONS (Augmenter, Diminuer, Supprimer) ---

$action = $_GET['action'] ?? null;
$id_produit = $_GET['id'] ?? null;

if ($action && $id_produit !== null) {
    if (isset($panier[$id_produit])) {

        switch ($action) {
            case 'increase':
                // Augmenter la quantité
                $panier[$id_produit]['quantite']++;
                break;

            case 'decrease':
                // Diminuer la quantité
                $panier[$id_produit]['quantite']--;

                // Si la quantité tombe à zéro ou moins, supprimer l'article
                if ($panier[$id_produit]['quantite'] <= 0) {
                    unset($panier[$id_produit]);
                }
                break;

            case 'remove':
                // Supprimer complètement l'article (corbeille)
                unset($panier[$id_produit]);
                break;
        }

        // Mettre à jour la session avec le nouveau panier
        $_SESSION['panier'] = $panier;

        // Rediriger pour éviter la re-soumission du formulaire/lien
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }
}

// --- FIN GESTION DES ACTIONS ---
?>

<div class="container py-5">
    <h2 class="mb-4 fw-bold">Votre panier</h2>
    <div class="row">

        <div class="col-lg-8">
            <?php if (empty($panier)): ?>
                <p>Votre panier est vide.</p>

            <?php else: ?>
                <?php
                foreach ($panier as $id => $produit):

                    // Total par ligne
                    $lineTotal = $produit['prix'] * $produit['quantite'];
                    $subtotal += $lineTotal;
                    ?>

                    <div class="cart-item d-flex align-items-center p-3 mb-4 shadow-sm rounded-4">

                        <img src="<?= $produit['image'] ?>" alt="image produit" class="item-img rounded">

                        <div class="ms-3 flex-grow-1">
                            <h5 class="fw-semibold mb-1"><?= $produit['name'] ?></h5>
                            <p class="text-muted small"><?= $produit['description'] ?></p>

                            <div class="d-flex align-items-center quantity-box">
                                <a href="?action=decrease&id=<?= $id ?>" class="btn btn-light">−</a>

                                <p class="mx-3"><?= $produit['quantite'] ?></p>

                                <a href="?action=increase&id=<?= $id ?>" class="btn btn-light">+</a>
                            </div>
                        </div>

                        <div class="text-end">
                            <p class="item-price"><?= number_format($lineTotal, 2) ?>€</p>
                            <small class="text-muted"><?= number_format($produit['prix'], 2) ?>€ pièce</small>
                            <div class="mt-2">
                                <a href="?action=remove&id=<?= $id ?>" class="text-danger">
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
            // Calculs finaux (utilisent $subtotal, qui est garanti d'exister)
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

                <a href="http://localhost/savouinos/?page=commande" class="btn btn-dark w-100 mb-3">Finaliser la commande</a>
                <a href="http://localhost/savouinos/?page=catalogue" class="btn btn-outline-secondary w-100">Continuer mes achats</a>
            </div>

        </div>
    </div>
</div>