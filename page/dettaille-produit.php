<?php

$row = '';

if (!isset($_GET['idproduit']) || empty($_GET['idproduit'])) {
    echo "ID produit manquant";
}

$id = (int) $_GET['idproduit'];

// Récupération du produit
$sql = "SELECT * FROM produit WHERE idproduit = $id";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    header("https://localhost/savouinos/?page=zdvf");
}

$produit = mysqli_fetch_assoc($result);
?>

<section>
    <h2><?= htmlspecialchars($produit['nom']) ?></h2>
    <div>
        <img src="<?= '/savouinos/public/asset/img/' . htmlspecialchars($produit['image']) ?>" alt="<?= $produit["nom"]?> ">
    </div>
    <div>
        <p><?= htmlspecialchars($produit['description']) ?></p>
        <p><strong>Stock :</strong> <?= htmlspecialchars($produit['stock']) ?></p>
        <h3><strong>prix :</strong> <?= htmlspecialchars($produit['prix']) ?> <strong>€</strong></h3>
    <div>
        <a href="http://localhost/savouinos/public/includes/panier_add.php?id=<?= $produit['idproduit'] ?>">
            Ajouter au panier
        </a>
    </div>
    <div>
        <a href="#">Catalogue</a>
    <div>
</section>


