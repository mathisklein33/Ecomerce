<?php

require '../public/config/config.php';

// Vérifier connexion
if (!$conn) {
    die("Erreur de connexion MySQL : " . mysqli_connect_error());
}
// récupére base de donnée
$sql = "
SELECT
    commande.idcommande,
    commande.quantite,
    codepromo.codepromocol AS codepromo,
    commande.user_iduser,
    user.firstname,
    user.surname
FROM commande
INNER JOIN user ON user.iduser = commande.user_iduser
LEFT JOIN codepromo 
    ON commande.codepromo_idcodepromo = codepromo.idcodepromo
ORDER BY commande.idcommande DESC
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
        </div>
    </section>

    <section>
        <div>
            <h1>Liste des commande</h1>

            <div>
                <?php
                while($row = mysqli_fetch_assoc($result)) : // boucle crée carde automatique
                    ?>

                    <div>
                        <h5><strong>n° commande :</strong><?= htmlspecialchars($row['idcommande']) ?></h5>

                        <p><strong>Code promo :</strong> <?= htmlspecialchars($row['codepromo'] ?? "Aucun") ?></p>

                        <p>
                            <?= htmlspecialchars($row['quantite']) ?>
                        </p>

                        <p>

                            <strong>Client :</strong>
                            <?= htmlspecialchars($row['firstname']) ?>
                            <?= htmlspecialchars($row['surname']) ?>
                        </p>
                    </div>

                <?php endwhile; //boucle fin?>
            </div>

        </div>
    </section>

