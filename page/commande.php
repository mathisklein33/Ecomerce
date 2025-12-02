<?php
// Vérifier si le panier existe
if (!isset($_SESSION['panier']) || empty($_SESSION['panier'])) {
    die("Panier vide.");
}

$panier = $_SESSION['panier'];

// Données client par défaut
$client_data = [
        "firstname" => "",
        "surname"   => "",
        "email"     => "",
        "adresse"   => ""
];

// Si utilisateur connecté → récupérer les infos
if (isset($_SESSION['user_id'])) {

    $stmt = mysqli_prepare($conn, "
        SELECT iduser, surname, firstname, email, adresse 
        FROM user 
        WHERE iduser = ?
    ");
    mysqli_stmt_bind_param($stmt, "i", $_SESSION['user_id']);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $client_data = mysqli_fetch_assoc($result) ?: $client_data;
    }

    mysqli_stmt_close($stmt);
}

// Si formulaire envoyé
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {

    $nom     = htmlspecialchars(trim($_POST['surname']));
    $prenom  = htmlspecialchars(trim($_POST['firstname']));
    $email   = htmlspecialchars(trim($_POST['email']));
    $adresse = htmlspecialchars(trim($_POST['adresse']));

    // Quantité totale
    $quantite = array_sum(array_column($panier, 'quantite'));

    // Code promo → NULL si vide
    $codePromo = !empty($_POST['codepromo']) ? trim($_POST['codepromo']) : NULL;

    // User
    $user_id = $_SESSION['user_id'] ?? NULL;
    $role_id = $_SESSION['user_role'] ?? 2;

    // Insert commande
    $stmt = mysqli_prepare($conn, "
        INSERT INTO commande (codepromo_idcodepromo, quantite, date, user_iduser, user_role_idrole)
        VALUES (?, ?, NOW(), ?, ?)
    ");

    mysqli_stmt_bind_param(
            $stmt,
            "iiii",
            $codePromo,
            $quantite,
            $user_id,
            $role_id
    );

    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Vider panier
    $_SESSION['panier'] = [];

    // Redirection correcte vers SAVOUINOS
    header("Location: http://localhost/savouinos/?page=confirmation");
    exit;
}

// Fonction total
function calculerTotal($panier) {
    $total = 0;
    if (!is_array($panier)) return 0;

    foreach ($panier as $item) {
        $prix = isset($item['prix']) ? (float)$item['prix'] : 0;
        $qte  = isset($item['quantite']) ? (int)$item['quantite'] : 0;
        $total += $prix * $qte;
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
                    $prix = isset($item['prix']) ? (float)$item['prix'] : 0;
                    $qte  = isset($item['quantite']) ? (int)$item['quantite'] : 0;
                    $total_ligne = $prix * $qte;

                    // fallback pour le nom
                    $produit_nom = $item['nom'] ?? $item['name'] ?? '';
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($produit_nom); ?></td>
                        <td><?php echo number_format($prix, 2, ',', ' '); ?> €</td>
                        <td><?php echo $qte; ?></td>
                        <td><?php echo number_format($total_ligne, 2, ',', ' '); ?> €</td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
