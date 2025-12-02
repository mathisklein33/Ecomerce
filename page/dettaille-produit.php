<?php
require '../public/config/config.php';

$row = '';

if (!isset($_GET['idproduit']) || empty($_GET['idproduit'])) {
    echo "ID produit manquant";
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
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Détaille du produit</title>
    <link rel="stylesheet" href="../public/asset/CSS/detaille.css">
</head>

<section>
    <h2><?= htmlspecialchars($produit['nom']) ?></h2>
    <div>
        <img src="../public/asset/img/<?= htmlspecialchars($row['image']) ?>" alt="">
    </div>
    <div>
        <p><?= htmlspecialchars($row['description']) ?></p>
        <p><strong>Stock :</strong> <?= htmlspecialchars($row['stock']) ?></p>
        <h3><strong>prix :</strong> <?= htmlspecialchars($row['prix']) ?> <strong>€</strong></h3>
    </div>
    <div>
        <a href="https://localhost/savouinos/?page=catalogue">retoure catalogue</a>
        <a href="#">acheter</a>
    </div>
        
    <div>
       <p>
           typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
       </p>
    </div>
</section>


