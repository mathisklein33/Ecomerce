<?php
// Vérifier si le panier existe
if (!isset($_SESSION['panier']) || empty($_SESSION['panier'])) {
    die("Panier vide.");
}

$panier = $_SESSION['panier'];
$client_data = ["firstname" => "", "surname" => "", "email" => "", "adresse" => ""];

// Si utilisateur connecté → récupérer les infos
if (isset($_SESSION['user_id'])) {
    $stmt = mysqli_prepare($conn, "SELECT iduser, surname, firstname, email, adresse FROM user WHERE iduser = ?");
    mysqli_stmt_bind_param($stmt, "i", $_SESSION['user_id']);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($result && mysqli_num_rows($result) > 0) {
        $client_data = mysqli_fetch_assoc($result) ?: $client_data;
    }
    mysqli_stmt_close($stmt);
}

$promo_error = "";
$promo_success = "";
$reduction_pourcentage = 0;

// Si formulaire envoyé
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
    $appliquer_promo = isset($_POST['action']) && $_POST['action'] === 'appliquer_promo';
    $nom = htmlspecialchars(trim($_POST['surname']));
    $prenom = htmlspecialchars(trim($_POST['firstname']));
    $email = htmlspecialchars(trim($_POST['email']));
    $adresse = htmlspecialchars(trim($_POST['adresse']));
    $quantite = array_sum(array_column($panier, 'quantite'));
    $codePromoId = 1;

    // Vérifier code promo
    if (!empty($_POST['codepromo'])) {
        $stmt_promo = mysqli_prepare($conn, "SELECT idcodepromo, reduction FROM codepromo WHERE code = ?");
        $vars = trim($_POST['codepromo']);
        mysqli_stmt_bind_param($stmt_promo, "s", $vars);
        mysqli_stmt_execute($stmt_promo);
        $result_promo = mysqli_stmt_get_result($stmt_promo);
        if ($result_promo && mysqli_num_rows($result_promo) > 0) {
            $promo_data = mysqli_fetch_assoc($result_promo);
            $codePromoId = $promo_data['idcodepromo'];
            $reduction_pourcentage = (float)$promo_data['reduction'];
            $promo_success = "Code promo appliqué avec succès !";
        } else {
            $promo_error = "Code promo invalide.";
        }
        mysqli_stmt_close($stmt_promo);
    }

    // Gestion utilisateur connecté ou invité
    $user_id = $_SESSION['user_id'] ?? NULL;
    $role_id = NULL;

    if ($user_id !== NULL) {
        $stmt_user = mysqli_prepare($conn, "SELECT role_idrole FROM user WHERE iduser = ?");
        mysqli_stmt_bind_param($stmt_user, "i", $user_id);
        mysqli_stmt_execute($stmt_user);
        $result_user = mysqli_stmt_get_result($stmt_user);
        if ($result_user && mysqli_num_rows($result_user) > 0) {
            $role_id = mysqli_fetch_assoc($result_user)['role_idrole'];
        }
        mysqli_stmt_close($stmt_user);
    } else {
        $role_invite = 3;
        $check_role = mysqli_query($conn, "SELECT idrole FROM role WHERE idrole = 3");
        if (mysqli_num_rows($check_role) == 0) {
            mysqli_query($conn, "INSERT INTO role (idrole, rolename) VALUES (3, 'invité')");
        }

        $stmt_check = mysqli_prepare($conn, "SELECT iduser, role_idrole FROM user WHERE email = ?");
        mysqli_stmt_bind_param($stmt_check, "s", $email);
        mysqli_stmt_execute($stmt_check);
        $result_check = mysqli_stmt_get_result($stmt_check);

        if ($result_check && mysqli_num_rows($result_check) > 0) {
            $existing_user = mysqli_fetch_assoc($result_check);
            $user_id = $existing_user['iduser'];
            $role_id = $existing_user['role_idrole'];
        } else {
            $stmt_guest = mysqli_prepare($conn, "INSERT INTO user (surname, firstname, email, password, role_idrole, adresse) VALUES (?, ?, ?, ?, ?, ?)");
            $password_temp = password_hash(uniqid(), PASSWORD_DEFAULT);
            mysqli_stmt_bind_param($stmt_guest, "ssssis", $nom, $prenom, $email, $password_temp, $role_invite, $adresse);
            mysqli_stmt_execute($stmt_guest);
            $user_id = mysqli_insert_id($conn);
            $role_id = $role_invite;
            mysqli_stmt_close($stmt_guest);
        }
        mysqli_stmt_close($stmt_check);
    }

    // Créer commande si pas d'erreur et pas juste pour appliquer promo
    if (empty($promo_error) && !$appliquer_promo) {
        try {
            $stmt = mysqli_prepare($conn, "INSERT INTO commande (codepromo_idcodepromo, quantite, date, user_iduser, user_role_idrole, statue) VALUES (?, ?, NOW(), ?, ?, 'en attente')");
            mysqli_stmt_bind_param($stmt, "iiii", $codePromoId, $quantite, $user_id, $role_id);
            if (!mysqli_stmt_execute($stmt)) die("Erreur commande : " . mysqli_error($conn));
            $commande_id = mysqli_insert_id($conn);
            mysqli_stmt_close($stmt);

            // Insérer produits
            foreach ($panier as $id_produit => $item) {
                $quantite_produit = isset($item['quantite']) ? (int)$item['quantite'] : (isset($item['quantity']) ? (int)$item['quantity'] : 1);
                $stmt_produit = mysqli_prepare($conn, "INSERT INTO commande_has_produit (commande_idcommande, commande_codepromo_idcodepromo, produit_idproduit, quantite) VALUES (?, ?, ?, ?)");
                mysqli_stmt_bind_param($stmt_produit, "iiii", $commande_id, $codePromoId, $id_produit, $quantite_produit);
                if (!mysqli_stmt_execute($stmt_produit)) die("Erreur produit : " . mysqli_error($conn));
                mysqli_stmt_close($stmt_produit);
            }

            $_SESSION['panier'] = [];
            header("Location: http://localhost/savouinos/?page=confirmation");
            exit;
        } catch (Exception $e) {
            die("Erreur : " . $e->getMessage());
        }
    }
}

// Fonction calcul total
function calculerTotal($panier, $reduction = 0) {
    $total = 0;
    if (!is_array($panier)) return 0;
    foreach ($panier as $item) {
        $total += ((float)($item['prix'] ?? 0)) * ((int)($item['quantite'] ?? 0));
    }
    if ($reduction > 0) $total = $total * (1 - ($reduction / 100));
    return $total + ($total * 0.08);
}

$total_commande = calculerTotal($panier, $reduction_pourcentage);
$total_sans_reduction = calculerTotal($panier, 0);
?>

<div class="checkout-page">
    <h2 class="page-title">Finaliser la commande</h2>
    <div class="container checkout-container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="checkout-card">
                    <h3 class="section-title">1. Informations de livraison</h3>
                    <?php if (!empty($promo_error)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($promo_error); ?></div>
                    <?php endif; ?>
                    <?php if (!empty($promo_success)): ?>
                        <div class="alert alert-success"><?= htmlspecialchars($promo_success); ?></div>
                    <?php endif; ?>

                    <form action="#" method="POST" id="checkout-form">
                        <div class="form-group">
                            <label for="firstname">Prénom</label>
                            <input type="text" id="firstname" name="firstname" value="<?= htmlspecialchars($client_data['firstname']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="surname">Nom</label>
                            <input type="text" id="surname" name="surname" value="<?= htmlspecialchars($client_data['surname']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" value="<?= htmlspecialchars($client_data['email']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="adresse">Adresse de livraison</label>
                            <textarea id="adresse" name="adresse" rows="3" required><?= htmlspecialchars($client_data['adresse']); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label>Code Promo (optionnel)</label>
                            <div class="promo-group">
                                <input type="text" id="codepromo" name="codepromo" placeholder="EX: SAVOIR15" value="<?= htmlspecialchars($_POST['codepromo'] ?? ''); ?>">
                                <input type="hidden" name="action" value="" id="form_action">
                                <button type="button" class="btn-promo" onclick="appliquerPromo(event)">Appliquer</button>
                            </div>
                        </div>
                        <?php if ($reduction_pourcentage > 0): ?>
                            <div class="alert alert-info">
                                Réduction de <?= $reduction_pourcentage; ?>% appliquée !
                                Économie : <?= number_format($total_sans_reduction - $total_commande, 2, ',', ' '); ?> €
                            </div>
                        <?php endif; ?>
                        <button type="button" class="btn-submit" onclick="commanderMaintenant(event)">
                            Confirmer & Commander (<?= number_format($total_commande, 2, ',', ' '); ?> €)
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="checkout-card">
                    <h3 class="section-title">2. Récapitulatif du panier</h3>
                    <table class="recap-table">
                        <thead>
                        <tr><th>Produit</th><th>Prix</th><th>Quantité</th><th>Total</th></tr>
                        </thead>
                        <tbody>
                        <?php foreach ($panier as $id => $item):
                            $prix = (float)$item['prix'];
                            $qte = (int)$item['quantite'];
                            $produit_nom = $item['nom'] ?? $item['name'] ?? '';
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($produit_nom); ?></td>
                                <td><?= number_format($prix, 2, ',', ' '); ?> €</td>
                                <td><?= $qte; ?></td>
                                <td><?= number_format($prix * $qte, 2, ',', ' '); ?> €</td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="recap-total">
                        <?php if ($reduction_pourcentage > 0): ?>
                            <div style="text-decoration: line-through; color: #999;">
                                Avant réduction : <?= number_format($total_sans_reduction, 2, ',', ' '); ?> €
                            </div>
                        <?php endif; ?>
                        Total : <strong><?= number_format($total_commande, 2, ',', ' '); ?> €</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function appliquerPromo(e) {
        if (e) e.preventDefault();
        if (document.getElementById('codepromo').value.trim() === '') {
            alert('Veuillez saisir un code promo');
            return false;
        }
        document.getElementById('form_action').value = 'appliquer_promo';
        document.getElementById('checkout-form').submit();
    }

    function commanderMaintenant(e) {
        if (e) e.preventDefault();
        var form = document.getElementById('checkout-form');
        if (!form.checkValidity()) {
            form.reportValidity();
            return false;
        }
        document.getElementById('form_action').value = 'commander';
        form.submit();
    }
</script>