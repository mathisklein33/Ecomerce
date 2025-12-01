<?php

// ----- TRAITEMENT AJAX (sans changer de page) -----
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax'])) {

    $panier = $_SESSION['panier'] ?? [];
    $action = $_POST['action'];
    $id = $_POST['id'];

    if (isset($panier[$id])) {

        switch ($action) {
            case 'increase':
                $panier[$id]['quantite']++;
                break;

            case 'decrease':
                $panier[$id]['quantite']--;
                if ($panier[$id]['quantite'] <= 0) {
                    unset($panier[$id]);
                }
                break;

            case 'remove':
                unset($panier[$id]);
                break;
        }

        $_SESSION['panier'] = $panier;
    }

    // Recalcule du sous-total
    $subtotal = 0;
    foreach ($panier as $p) {
        $subtotal += $p['prix'] * $p['quantite'];
    }

    echo json_encode([
            'success' => true,
            'quantity' => $panier[$id]['quantite'] ?? 0,
            'subtotal' => $subtotal
    ]);
    exit;
}

// ----- AFFICHAGE NORMAL -----
$panier = $_SESSION['panier'] ?? [];
$subtotal = 0;
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
                    ?>

                    <div class="cart-item d-flex align-items-center p-3 mb-4 shadow-sm rounded-4">

                        <img src="<?= '/savouinos/public/asset/img/' . htmlspecialchars($produit['image']) ?>" alt="image produit" class="item-img rounded">

                        <div class="ms-3 flex-grow-1">
                            <h5 class="fw-semibold mb-1"><?= $produit['name'] ?></h5>
                            <p class="text-muted small"><?= $produit['description'] ?></p>

                            <div class="d-flex align-items-center quantity-box">

                                <button class="btn btn-light update-cart" data-action="decrease" data-id="<?= $id ?>">−</button>

                                <p class="mx-3 quantite" data-id="<?= $id ?>"><?= $produit['quantite'] ?></p>

                                <button class="btn btn-light update-cart" data-action="increase" data-id="<?= $id ?>">+</button>

                            </div>
                        </div>

                        <div class="text-end">
                            <p class="item-price"><?= number_format($lineTotal, 2) ?>€</p>
                            <small class="text-muted"><?= number_format($produit['prix'], 2) ?>€ pièce</small>
                            <div class="mt-2">
                                <button class="text-danger update-cart" data-action="remove" data-id="<?= $id ?>">
                                    <i class="bi bi-trash trash-icon"></i>
                                </button>
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
                    <p id="subtotal"><?= number_format($subtotal, 2) ?>€</p>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <p>Livraison</p>
                    <p>GRATUIT</p>
                </div>

                <div class="d-flex justify-content-between mb-3">
                    <p>Taxe (8%)</p>
                    <p id="tax"><?= number_format($tax, 2) ?>€</p>
                </div>

                <hr>
                <div class="d-flex justify-content-between fw-bold mb-4">
                    <p>Total</p>
                    <p class="text-primary" id="total"><?= number_format($total, 2) ?>€</p>
                </div>

                <a href="http://localhost/savouinos/?page=commande" class="btn btn-dark w-100 mb-3">Finaliser la commande</a>
                <a href="http://localhost/savouinos/?page=catalogue" class="btn btn-outline-secondary w-100">Continuer mes achats</a>
            </div>

        </div>
    </div>
</div>

<!-- ----- JAVASCRIPT AJAX ----- -->

<script>
    document.querySelectorAll('.update-cart').forEach(btn => {
        btn.addEventListener('click', () => {

            let id = btn.dataset.id;
            let action = btn.dataset.action;

            fetch("", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `ajax=1&action=${action}&id=${id}`
            })
                .then(res => res.json())
                .then(data => {

                    if (data.success) {

                        // Mise à jour quantité visuelle
                        let q = document.querySelector(`.quantite[data-id="${id}"]`);
                        if (q) q.textContent = data.quantity;

                        // Mise à jour sous-total
                        document.getElementById("subtotal").textContent = data.subtotal.toFixed(2) + "€";

                        // Mise à jour taxe et total
                        let tax = data.subtotal * 0.08;
                        let total = data.subtotal + tax;

                        document.getElementById("tax").textContent = tax.toFixed(2) + "€";
                        document.getElementById("total").textContent = total.toFixed(2) + "€";

                        // Si quantité = 0 → suppression de l'élément
                        if (data.quantity <= 0) {
                            btn.closest(".cart-item").remove();
                        }
                    }
                });
        });
    });
</script>