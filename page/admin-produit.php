<?php
include '../public/include/header.php';
require '../public/config/config.php';

// Vérifier connexion
if (!$conn) {
    die("Erreur de connexion MySQL : " . mysqli_connect_error());
}
// récupére base de donnée
$sql = "
SELECT
    produit.idproduit,
    produit.nom,
    produit.description,
    produit.prix,
    produit.stock,
    produit.image,
    produit.date
FROM produit
ORDER BY produit.idproduit DESC
";

$result = mysqli_query($conn, $sql); // récupére base de donnée

if (!$result) {
    die('Erreur SQL : ' . mysqli_error($conn));
}
?>

    <section>
        <div>
            <label for="site-search">Search the site:</label>
            <input type="search" name="q" />
            <button>Search</button>
        </div>
    </section>

    <section>
        <div>
            <h1>Liste des produits</h1>

            <div>
                <?php while($row = mysqli_fetch_assoc($result)) : // boucle crée carde automatique?>

                    <div>
                        <h5><?= htmlspecialchars($row['nom']) ?></h5>

                        <img src="../public/asset/img/<?= htmlspecialchars($row['image']) ?>" alt="">

                        <p><?= htmlspecialchars($row['description']) ?></p>

                        <p>
                            <strong>Stock :</strong> <?= htmlspecialchars($row['stock']) ?><br>
                        </p>

                        <p>
                            <strong>prix :</strong> <?= htmlspecialchars($row['prix']) ?>
                        </p>
                    </div>

                <?php endwhile; //boucle fin?>
            </div>

        </div>
    </section>

<?php include '../public/include/footer.php'; ?>