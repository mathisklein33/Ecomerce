<?php
if (!$conn) {
    die("Erreur de connexion MySQL : " . mysqli_connect_error());
}

$sql = "SELECT * FROM produit";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die('Erreur SQL : ' . mysqli_error($conn));
}

if (isset($_SESSION['panier'])) {
    $panier = $_SESSION['panier'] ?? [];
}
?>

<section class="border">

<input id="search" type="search" placeholder=" 🔍  Recherche un produit...">




<div class="container mt-4">
    <div class="row">

        <?php while($row = mysqli_fetch_assoc($result)) : ?>
            <div class="col-md-4 col-lg-3">
                <div class="product-card"
                     data-name="<?= htmlspecialchars($row['nom'], ENT_QUOTES) ?>"
                     data-description="<?= htmlspecialchars($row['description'], ENT_QUOTES) ?>">

                    <img src="<?= '/savouinos/public/asset/img/' . htmlspecialchars($row['image']) ?>"
                         alt="<?= htmlspecialchars($row['nom']) ?>">

                    <h5><?= htmlspecialchars($row['nom']) ?></h5>

                    <p class="price-tag"><?= htmlspecialchars($row['prix']) ?> €</p>

                    <div class="product-buttons">
                        <a class="btn-details .btncolor2 "
                           href="https://localhost/savouinos/?page=produits&idproduit=<?= $row['idproduit'] ?>">
                            Voir détails
                        </a>

                        <a class="btn-add btncolor"
                           href="http://localhost/savouinos/public/includes/panier_add.php?id=<?= $row['idproduit'] ?>">
                            Ajouter au panier 🛒
                        </a>
                    </div>

                </div>
            </div>
        <?php endwhile; ?>

    </div>
</div>
</section>


