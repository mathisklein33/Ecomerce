<?php
include 'public/includes/header.php';
require 'public/config/config.php';

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
            <a href="produit-creation.php">crée produit</a>
        </div>
    </section>

    <section>
        <div>
            <h1>Liste des produits</h1>

            <div>
                    <?php
                    while($row = mysqli_fetch_assoc($result)) : // boucle crée carde automatique
                    ?>

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

                        <button>supprimer</button>
                        <?php
                        // Action de SUPPRESSION de la fiche
                        if($_GET['action'] == "supprimer")
                        {
                            // Suppression de la Fiche N°xxx
                            $id = ($_GET['idproduit']); // Récuération du numéro de ligne à supprimer
                            $db->query("DELETE FROM $db_table WHERE id='$id'"); // Suppression

                            // redirection après suppression
                            header("location:index.php?nav=".$nav."&msg=2");
                        }
                        ?>
                    </div>

                <?php endwhile; //boucle fin?>
            </div>

        </div>
    </section>

<?php include 'public/includes/footer.php'; ?>