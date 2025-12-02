<?php

if (!$conn) {
    die("Erreur de connexion MySQL : " . mysqli_connect_error());
}

$sql = "
SELECT *
FROM produit
";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die('Erreur SQL : ' . mysqli_error($conn));
}
if (isset($_SESSION['panier'])){
    $panier = $_SESSION['panier'] ?? [];
}

//$panier = $panier + $add;
?>

<input id="search" type="search" placeholder="Recherche...">



<!--Creation des cards-->
<?php while($row = mysqli_fetch_assoc($result)) : ?>
    <div class="card"
         data-name="<?= htmlspecialchars($row['nom'], ENT_QUOTES) ?>"
         data-description="<?= htmlspecialchars($row['description'], ENT_QUOTES) ?>">

        <h5><?= htmlspecialchars($row['nom']) ?></h5>

        <img src="<?= '/savouinos/public/asset/img/' . htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['nom']) ?>">
        <p><strong>Prix :</strong> <?= htmlspecialchars($row['prix']) ?> €</p>
        <div>
            <a href="https://localhost/savouinos/?page=produits&idproduit=<?= $row['idproduit'] ?>">Détails</a>
        </div>
        <div>
            <a href="http://localhost/savouinos/public/includes/panier_add.php?id=<?= $row['idproduit'] ?>">
                Ajouter au panier
            </a>
        </div>
    </div>
<?php endwhile; ?>

