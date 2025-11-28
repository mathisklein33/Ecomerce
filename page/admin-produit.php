<?php

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


if (isset($_GET['action']) && $_GET['action'] === 'supprimer' && isset($_GET['idproduit'])) {
    $id = (int) $_GET['idproduit'];

    // Préparation + exécution (sécurisé)
    $stmt = mysqli_prepare($conn, "DELETE FROM produit WHERE idproduit = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);

        // Vérifier réussite
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            // succès
            mysqli_stmt_close($stmt);
            // Rediriger pour éviter double suppression au refresh
            header("Location: " . strtok($_SERVER["REQUEST_URI"], '?')); // retour sur la même page sans query
            exit;
        } else {
            // aucun enregistrement supprimé — id inexistant ou erreur
            mysqli_stmt_close($stmt);
            // Pour debug (retirer en production)
            error_log("Suppression échouée pour idproduit={$id}");
        }
    } else {
        error_log("Erreur prepare: " . mysqli_error($conn));
    }
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
                            <strong>Stock :</strong> <?= htmlspecialchars($row['stock']) ?> <br>
                        </p>

                        <p>
                            <strong>prix :</strong> <?= htmlspecialchars($row['prix']) ?> <strong>€</strong>
                        </p>

                        <a href="delete.php?idproduit=<?= $row['idproduit'] ?>"
                           onclick="return confirm('Voulez-vous vraiment supprimer ce produit ?');">
                            <button type="button">Supprimer</button>
                        </a>

                        <a href="modifier.php?idproduit=<?= $row['idproduit'] ?>">
                            <button type="button">Modifier</button>
                        </a>
                    </div>

                <?php endwhile; //boucle fin?>
            </div>

        </div>
    </section>

