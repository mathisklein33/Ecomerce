<?php
// Traiter la mise à jour AVANT de récupérer les commandes
if(!empty($_POST['statue']) && !empty($_POST['idcommande'])) {
    $etat = $_POST['statue'];
    $idCommande = $_POST['idcommande'];

    $sqlStatue = "UPDATE `commande` SET `statue` = ? WHERE `idcommande` = ?";
    $stmtStatue = $conn->prepare($sqlStatue);
    $stmtStatue->bind_param("si", $etat, $idCommande);
    $stmtStatue->execute();
    $stmtStatue->close();

    // Redirection pour éviter la resoumission du formulaire
    header("Location: http://localhost/savouinos/?page=admin/command");
    exit();
}

// Récupérer les commandes
$sql = "SELECT commande.*, user.surname, user.firstname, user.email, user.adresse
    FROM commande   
    LEFT JOIN user ON commande.user_iduser = user.iduser
    WHERE 1
    ORDER BY commande.date DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
?>

<div>
    <div>
        <?php if($result->num_rows === 0): ?>
            <div><p>Aucune commande</p></div>
        <?php else: ?>
            <?php while ($com = $result->fetch_assoc()): ?>
                <div class="mb-3">
                    <div>
                        <div>
                            <h4>👤 Informations client</h4>
                            <p><strong>Nom :</strong> <?= htmlspecialchars($com['surname']) ?> <?= htmlspecialchars($com['firstname']) ?></p>
                            <p><strong>Email :</strong> <?= htmlspecialchars($com['email']) ?></p>
                            <p><strong>Adresse :</strong> <?= htmlspecialchars($com['adresse']) ?></p>
                        </div>

                        <p>Numéro de commande : <?= htmlspecialchars($com['idcommande']) ?></p>
                        <p>Date : <?= htmlspecialchars($com['date']) ?></p>
                        <p>Quantité : <?= htmlspecialchars($com['quantite']) ?> produits</p>

                        <?php
                        $sqlProduits = "SELECT commande_has_produit.*, produit.nom, produit.prix 
                                       FROM commande_has_produit
                                       LEFT JOIN produit ON commande_has_produit.produit_idproduit = produit.idproduit
                                       WHERE commande_has_produit.commande_idcommande = ?";
                        $stmtProduits = $conn->prepare($sqlProduits);
                        $stmtProduits->bind_param("i", $com['idcommande']);
                        $stmtProduits->execute();
                        $resultProduits = $stmtProduits->get_result();
                        ?>

                        <?php if($resultProduits->num_rows > 0):
                            $total = 0; ?>
                            <div>
                                <strong>Produits commandés :</strong>
                                <ul>
                                    <?php while($produit = $resultProduits->fetch_assoc()):
                                        $total += $produit['quantite'] * $produit['prix']; ?>
                                        <li>
                                            <?= htmlspecialchars($produit['nom']) ?>
                                            - Quantité : <?= htmlspecialchars($produit['quantite']) ?>
                                            - Prix unité : <?= $produit['prix']?>€
                                        </li>
                                    <?php endwhile; ?>
                                </ul>
                                <div>
                                    <p>Total : <?= $total ?>€</p>
                                </div>
                            </div>
                        <?php endif;
                        $stmtProduits->close(); ?>

                        <p><strong>État :</strong> <?= htmlspecialchars($com['statue'])?></p>

                        <form method="post">
                            <select name="statue" required>
                                <option value="">-- Modifier l'état --</option>
                                <option value="en attente" <?= $com['statue'] == 'en attente' ? 'selected' : '' ?>>En attente</option>
                                <option value="en cours" <?= $com['statue'] == 'en cours' ? 'selected' : '' ?>>En cours</option>
                                <option value="expédiée" <?= $com['statue'] == 'expédiée' ? 'selected' : '' ?>>Expédiée</option>
                                <option value="livré" <?= $com['statue'] == 'livré' ? 'selected' : '' ?>>Livré</option>
                                <option value="annulée" <?= $com['statue'] == 'annulée' ? 'selected' : '' ?>>Annulée</option>
                            </select>
                            <input type="hidden" name="idcommande" value="<?= htmlspecialchars($com['idcommande']) ?>">
                            <button type="submit">Mettre à jour</button>
                        </form>
                    </div>
                </div>
                <hr>
            <?php endwhile; ?>
        <?php endif; ?>

        <?php $stmt->close(); ?>
    </div>
</div>