<?php

$sqlCommande = "SELECT *
                FROM commande
                WHERE user_iduser = ?";
$stmtCommande = $conn->prepare($sqlCommande);
$iduser = htmlspecialchars($_GET["iduser"]);
$stmtCommande->bind_param("i", $iduser);
$stmtCommande->execute();
$resultCommande = $stmtCommande->get_result();
// SUPPRIMEZ CETTE LIGNE : $commande = $resultCommande->fetch_assoc();
?>

<div>
    <div>
        <?php if($resultCommande->num_rows === 0): ?>
            <div><p>Aucune commande</p></div>
        <?php else: ?>
            <?php while ($com = $resultCommande->fetch_assoc()): ?>
                <div class="mb-3">
                    <div>
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
                        // SUPPRIMEZ AUSSI CETTE LIGNE : $produit = $resultProduits->fetch_assoc();
                        ?>

                        <?php if($resultProduits->num_rows > 0):
                        $total = 0    ?>

                            <div>
                                <strong>Produits commandés :</strong>
                                <ul>
                                    <?php while($produit = $resultProduits->fetch_assoc()):
                                    $total += $produit['quantite'] * $produit['prix']   ?>
                                        <li>
                                            <?= htmlspecialchars($produit['nom']) ?>
                                            - Quantité : <?= htmlspecialchars($produit['quantite']) ?>
                                            - Prix unité : <?= htmlspecialchars($produit['prix']) ?>€
                                        </li>
                                    <?php endwhile; ?>
                                </ul>
                                <div>
                                    <p>Total : <?= $total ?>€</p>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php $stmtProduits->close(); ?>

                        <p>État : <?= htmlspecialchars($com['statue'])?></p>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>

        <?php $stmtCommande->close(); ?>
    </div>
</div>