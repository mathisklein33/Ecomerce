<?php
session_start();
// Vérification panier non vide
if (empty($_SESSION['panier'])) {
    die("Panier vide.");
}

// Récupération du panier
$panier = $_SESSION['panier'];

// Données client par défaut
$client_data = [
        "firstname" => "",
        "surname" => "",
        "email" => "",
        "adresse" => ""
];

// Si utilisateur connecté → récupérer info DB
if (isset($_SESSION['user_id'])) {

    $stmt = $pdo->prepare("SELECT iduser, surname, firstname, email, adresse FROM user WHERE iduser = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $client_data = $stmt->fetch(PDO::FETCH_ASSOC) ?: $client_data;
}


if(isset($_POST) && !empty($_POST)){

    $nom = htmlspecialchars(trim($_POST['surname']));
    $prenom= htmlspecialchars(trim($_POST['firstname']));
    $email= htmlspecialchars(trim($_POST['email']));
    $adresse  = htmlspecialchars(trim($_POST['adresse']));

    // Quantité totale du panier
    $quantite = array_sum(array_column($panier, 'quantite'));

    // Pas encore de code promo
    $codePromo = NULL;

    // User
    $user_id = $_SESSION['user_id'] ?? NULL;
    $role_id = $_SESSION['user_role'] ?? 2; // client

    $stmt = $pdo->prepare("INSERT INTO commande (codepromo_idcodepromo, quantite, date, user_iduser, user_role_idrole)VALUES (?, ?, NOW(), ?, ?)"
    );

    $stmt->execute([$codePromo, $quantite, $user_id, $role_id]);


    // Vider le panier
    $_SESSION['panier'] = [];

    header('Location: http://localhost/e-commerce/?page=confirmation');
    exit;
}

//Fonction calcul total
function calculerTotal($panier) {
    $total = 0;
    foreach ($panier as $item) {
        $total += $item['prix'] * $item['quantite'];
    }
    return $total;
}

$total_commande = calculerTotal($panier);
?>


<div>
    <h2>🚚 Finaliser la Commande</h2>

    <div>

        <div >
            <h3>1. Vos Coordonnées et Adresse de Livraison</h3>

            <form action="#" method="POST">

                <div >
                    <label for="firstname">Prénom : </label>
                    <input type="text" id="firstname" name="firstname" value="<?php echo htmlspecialchars($client_data['firstname']); ?>" required>
                </div>

                <div>
                    <label for="surname">Nom : </label>
                    <input type="text" id="surname" name="surname" value="<?php echo htmlspecialchars($client_data['surname']); ?>" required>
                </div>

                <div>
                    <label for="email">Email : </label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($client_data['email']); ?>" required>
                </div>

                <div>
                    <label for="adresse">Adresse de Livraison (Rue, Ville, Code Postal) : *</label>
                    <textarea id="adresse" name="adresse" rows="4" required><?php echo htmlspecialchars($client_data['adresse']); ?></textarea>
                </div>

                <div>
                    <h4>Code Promotionnel (Optionnel)</h4>
                    <input type="text" id="codepromo" name="codepromo" placeholder="Entrez votre code promo">
                    <button type="button" >Appliquer</button>
                </div>

                <div >
                    <p> Champs obligatoires</p>
                    <button type="submit" >Confirmer et Commander (<?php echo number_format($total_commande, 2, ',', ' '); ?> €)</button>
                </div>
            </form>
        </div>

        <div>
            <h3>2. Récapitulatif de votre Panier</h3>

            <table>
                <thead>
                <tr>
                    <th>Produit</th>
                    <th>Prix U.</th>
                    <th>Qté</th>
                    <th>Total</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($panier as $id => $item):
                    $total_ligne = $item['prix'] * $item['quantite'];
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['nom']); ?></td>
                        <td><?php echo number_format($item['prix'], 2, ',', ' '); ?> €</td>
                        <td><?php echo $item['quantite']; ?></td>
                        <td><?php echo number_format($total_ligne, 2, ',', ' '); ?> €</td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

            <div>
                <p>Sous-total : <?php echo number_format($total_commande, 2, ',', ' '); ?> €</p>
                <p >TOTAL FINAL : <?php echo number_format($total_commande, 2, ',', ' '); ?> €</p>
            </div>

            <a href="panier.php">Modifier le panier</a>
        </div>
    </div>
</div>




