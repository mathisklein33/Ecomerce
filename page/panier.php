
<?php
include 'public/includes/header.php';
?>
<div class="container py-5">

    <h2 class="mb-4 fw-bold">Votre panier</h2>

    <div class="row">


        <div class="col-lg-8">


            <div class="cart-item d-flex align-items-center p-3 mb-4 shadow-sm rounded-4">
                <img src="" alt="image produit" class="item-img rounded">

                <div class="ms-3 flex-grow-1">
                    <h5 class="fw-semibold mb-1">produit 1</h5>
                    <p class="text-muted small">catégorie</p>

                    <div class="d-flex align-items-center quantity-box">
                        <button class="btn btn-light">−</button>
                        <p class="mx-3">7</p>
                        <button class="btn btn-light">+</button>
                    </div>
                </div>

                <div class="text-end">
                    <p class="item-price">76,93€</p>
                    <small class="text-muted">10,99€ pièce</small>
                    <div class="mt-2">
                        <i class="bi bi-trash trash-icon"></i>
                    </div>
                </div>
            </div>


        <div class="col-lg-4">
            <div class="order-box shadow-sm p-4 rounded-4">

                <h2 class="fw-bold mb-4">Résumé de la commande</h2>

                <div class="d-flex justify-content-between mb-2">
                    <p>Total (1 article)</p>
                    <p>76,93€</p>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <p>Livraison</p>
                    <p>GRATUIT</p>
                </div>

                <div class="d-flex justify-content-between mb-3">
                    <p>Taxe (8%)</p>
                    <p>prix €</p>
                </div>

                <hr>

                <div class="d-flex justify-content-between fw-bold mb-4">
                    <p>Total</p>
                    <p class="text-primary">76,93€</p>
                </div>

                <button class="btn btn-dark w-100 mb-3">Finaliser la commande</button>
                <button class="btn btn-outline-secondary w-100">Continuer mes achats</button>
            </div>
        </div>
    </div>
</div>
    <?php
    include 'public/includes/footer.php';
    ?>

