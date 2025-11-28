<?php
require '../public/config/config.php';

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
?>

<input id="search" type="search" placeholder="Recherche...">



<!--Creation des cards-->
<?php while($row = mysqli_fetch_assoc($result)) : ?>
    <div class="card"
         data-name="<?= htmlspecialchars($row['nom'], ENT_QUOTES) ?>"
         data-description="<?= htmlspecialchars($row['description'], ENT_QUOTES) ?>">

        <h5><?= htmlspecialchars($row['nom']) ?></h5>

        <img src="../public/asset/img/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['nom']) ?>">

        <p><?= htmlspecialchars($row['description']) ?></p>
        <p><strong>Stock :</strong> <?= htmlspecialchars($row['stock']) ?></p>
        <p><strong>Prix :</strong> <?= htmlspecialchars($row['prix']) ?> €</p>
    </div>
<?php endwhile; ?>
