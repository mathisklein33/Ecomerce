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

<div class="checkout-page">

    <h2 class="page-title">Finaliser la commande</h2>

    <div class="container checkout-container">
        <div class="row justify-content-center">

            <!-- FORMULAIRE CLIENT -->
            <div class="col-lg-6">
                <div class="checkout-card">
                    <h3 class="section-title">1. Informations de livraison</h3>

                    <form action="#" method="POST">

                        <div class="form-group">
                            <label for="firstname">Prénom</label>
                            <input type="text" id="firstname" name="firstname"
                                   value="<?= htmlspecialchars($client_data['firstname']); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="surname">Nom</label>
                            <input type="text" id="surname" name="surname"
                                   value="<?= htmlspecialchars($client_data['surname']); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email"
                                   value="<?= htmlspecialchars($client_data['email']); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="adresse">Adresse de livraison</label>
                            <textarea id="adresse" name="adresse" rows="3" required><?= htmlspecialchars($client_data['adresse']); ?></textarea>
                        </div>

                        <div class="form-group">
                            <label>Code Promo (optionnel)</label>
                            <div class="promo-group">
                                <input type="text" id="codepromo" name="codepromo" placeholder="EX: SAVOIR15">
                                <button type="button" class="btn-promo">Appliquer</button>
                            </div>
                        </div>

                        <button type="submit" class="btn-submit">
                            Confirmer & Commander (<?= number_format($total_commande, 2, ',', ' '); ?> €)
                        </button>

                    </form>
                </div>
            </div>

            <!-- RÉCAP PANIER -->
            <div class="col-lg-5">
                <div class="checkout-card">
                    <h3 class="section-title">2. Récapitulatif du panier</h3>

                    <table class="recap-table">
                        <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Prix</th>
                            <th>Quantité</th>
                            <th>Total</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php foreach ($panier as $id => $item):
                            $prix = (float)$item['prix'];
                            $qte  = (int)$item['quantite'];
                            $total_ligne = $prix * $qte;
                            $produit_nom = $item['nom'] ?? $item['name'] ?? '';
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($produit_nom); ?></td>
                                <td><?= number_format($prix, 2, ',', ' '); ?> €</td>
                                <td><?= $qte; ?></td>
                                <td><?= number_format($total_ligne, 2, ',', ' '); ?> €</td>
                            </tr>
                        <?php endforeach; ?>

                        </tbody>
                    </table>

                    <div class="recap-total">
                        Total : <strong><?= number_format($total_commande, 2, ',', ' '); ?> €</strong>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

