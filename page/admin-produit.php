<?php
if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
    header("Location: index.php");
    exit;
}
// Vérifier connexion
if (!$conn) {
    die("Erreur de connexion MySQL : " . mysqli_connect_error());
}

// Récupération des produits
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

$result = mysqli_query($conn, $sql);

if (!$result) {
    die('Erreur SQL : ' . mysqli_error($conn));
}


// SUPPRESSION D'UN PRODUIT
if (isset($_GET['action'], $_GET['idproduit']) && $_GET['action'] === 'supprimer') {

    $id = (int) $_GET['idproduit'];

    $stmt = mysqli_prepare($conn, "DELETE FROM produit WHERE idproduit = ?");
    if ($stmt) {

        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) > 0) {

            mysqli_stmt_close($stmt);

            // Redirection propre
            header("Location: http://localhost/savouinos/?page=admin/produit&msg=deleted");
            exit;

        } else {
            mysqli_stmt_close($stmt);
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
        <a href="http://localhost/savouinos/?page=produit/creation">crée produit</a>
    </div>
</section>

<section>
    <div>
        <h1>Liste des produits</h1>

        <div>
            <?php while($row = mysqli_fetch_assoc($result)) : ?>

                <div>
                    <h5><?= htmlspecialchars($row['nom']) ?></h5>

                    <img src="<?= '/savouinos/public/asset/img/' . htmlspecialchars($row['image']) ?>" alt="">

                    <p><?= htmlspecialchars($row['description']) ?></p>

                    <p>
                        <strong>Stock :</strong> <?= htmlspecialchars($row['stock']) ?>
                    </p>

                    <p>
                        <strong>Prix :</strong> <?= htmlspecialchars($row['prix']) ?> €
                    </p>

                    <a href="http://localhost/savouinos/public/includes/delete.php?idproduit=<?= $row['idproduit'] ?>"
                       onclick="return confirm('Voulez-vous vraiment supprimer ce produit ?');">
                        <button type="button">Supprimer</button>
                    </a>

                    <a href="/savouinos/page/modifier.php?idproduit=<?= $row['idproduit'] ?>">
                        <button type="button">Modifier</button>
                    </a>
                </div>

            <?php endwhile; ?>
        </div>

    </div>
</section>