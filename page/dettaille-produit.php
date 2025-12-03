<?php

if (!isset($_GET['idproduit']) || empty($_GET['idproduit'])) {
    die("ID produit manquant");
}

$id = (int) $_GET['idproduit'];

// Récupération du produit
$sql = "SELECT * FROM produit WHERE idproduit = $id";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    die("Produit introuvable");
}

$produit = mysqli_fetch_assoc($result);

?>

<section class="product-section border">

    <div class="container py-5">

        <!-- Carte du produit -->
        <div class="card product-card shadow-lg mx-auto">
            <div class="card-body">

                <h2 class="product-title text-center mb-4">
                    <?= htmlspecialchars($produit['nom']) ?>
                </h2>

                <div class="product-image-container text-center mb-4">
                    <img src="<?= '/savouinos/public/asset/img/' . htmlspecialchars($produit['image']) ?>"
                         alt="<?= htmlspecialchars($produit['nom']) ?>"
                         class="product-image img-fluid rounded">
                </div>

                <p class="product-description"><?= htmlspecialchars($produit['description']) ?></p>

                <p class="product-stock">
                    <strong>Stock :</strong> <?= htmlspecialchars($produit['stock']) ?>
                </p>

                <h4 class="product-price">
                    <strong>Prix :</strong> <?= htmlspecialchars($produit['prix']) ?> €
                </h4>

                <div class="product-buttons mt-4 d-flex gap-3">
                    <a href="https://localhost/savouinos/?page=catalogue" class="btn btn-secondary w-50">
                        Retour au catalogue
                    </a>
                    <a href="http://localhost/savouinos/public/includes/panier_add.php?id=<?= $produit['idproduit'] ?>" class="btn btn-primary w-50">
                        Ajouter au panier 🛒
                    </a>
                </div>

            </div>
        </div>

        <!-- Conditions de vente -->
        <div class="card conditions-card mt-4 shadow-lg mx-auto">
            <div class="card-body">
                <h5 class="conditions-title mb-3">Conditions de vente</h5>

                <p>
                    Les produits présentés sur ce site sont proposés dans la limite des stocks disponibles.
                    Les prix indiqués sont exprimés en euros et toutes taxes comprises.
                    La commande n’est validée qu’après confirmation du paiement et un email de confirmation sera envoyé au client.
                </p>

                <p>
                    Les livraisons sont effectuées à l’adresse fournie lors de la commande. Les délais peuvent varier selon le transporteur.
                    Le client dispose de 14 jours pour exercer son droit de rétractation, sous réserve que l’article soit retourné dans son état d’origine.
                </p>

                <p>
                    En cas de produit défectueux ou d’erreur, un échange ou un remboursement peut être demandé.
                    L’utilisation de ce site implique l’acceptation pleine et entière des présentes conditions de vente.
                </p>
            </div>
        </div>

    </div>
</section>