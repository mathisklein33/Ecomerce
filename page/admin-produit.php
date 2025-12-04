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
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Page produit admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../public/asset/CSS/style.css">

</head>
<body>

</body>
</html>

<section class="admin-container">

    <div class="search-box mb-4">
        <label>Rechercher :</label>
        <input id="search" class="form-control d-inline-block w-50" type="search" name="q">
        <a href="http://localhost/savouinos/?page=produit/creation" class="btn btn-success ms-3">
            Créer un produit
        </a>
    </div>
    <div>
        <a href="http://localhost/savouinos/?page=admin/command">Listes des commandes</a>
    </div>
    <div class="admin-header">
        <h1 class="text-center">Liste des produits</h1>
    </div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 justify-content-start p-4">
        <?php while($row = mysqli_fetch_assoc($result)) : ?>
            <div class="col d-flex">
                <div class="product-card w-100"
                     data-name="<?= htmlspecialchars($row['nom']) ?>"
                     data-description="<?= htmlspecialchars($row['description']) ?>">

                <h5 class="fw-bold"><?= htmlspecialchars($row['nom']) ?></h5>
                    <img src="<?= '/savouinos/public/asset/img/' . htmlspecialchars($row['image']) ?>" alt="">
                    <p><?= htmlspecialchars($row['description']) ?></p>
                    <p><strong>Stock :</strong> <?= htmlspecialchars($row['stock']) ?></p>
                    <p><strong>Prix :</strong> <?= htmlspecialchars($row['prix']) ?> €</p>

                    <div class="d-flex flex-wrap gap-2">
                        <a href="http://localhost/savouinos/public/includes/delete.php?idproduit=<?= $row['idproduit'] ?>"
                           onclick="return confirm('Supprimer ce produit ?');"
                           class="btn btn-delete btn-action">
                            Supprimer
                        </a>

                        <a href="http://localhost/savouinos/?page=produit/modifier&idproduit=<?= $row['idproduit'] ?>"
                           class="btn btn-edit btn-action">
                            Modifier
                        </a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>


</section>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Normalise : retire accents et met en minuscule (compatible)
        function normalizeText(s) {
            if (!s) return '';
            return s
                .normalize('NFD')                // sépare lettre + diacritiques
                .replace(/[\u0300-\u036f]/g, '') // supprime les diacritiques (accents)
                .toLowerCase();
        }

        const input = document.getElementById('search');
        if (!input) {
            console.error('Recherche : élément #search introuvable');
            return;
        }

        const cards = Array.from(document.querySelectorAll('.product-card'));
        if (!cards.length) {
            console.warn('Recherche : aucune .product-card trouvée');
            return;
        }

        input.addEventListener('input', function () {
            const raw = this.value || '';
            const query = normalizeText(raw.trim());

            if (query === '') {
                // champ vide : tout afficher
                cards.forEach(card => card.style.display = '');
                return;
            }

            const tokens = query.split(/\s+/).filter(Boolean); // mots-clés

            cards.forEach(card => {
                // lecture safe des data-attributes (fallback aux attributs HTML)
                const nameRaw = card.dataset && card.dataset.name ? card.dataset.name : (card.getAttribute('data-name') || '');
                const descRaw = card.dataset && card.dataset.description ? card.dataset.description : (card.getAttribute('data-description') || '');

                const hay = (normalizeText(nameRaw) + ' ' + normalizeText(descRaw)).trim();

                // tous les tokens doivent être présents
                const matches = tokens.every(t => hay.includes(t));

                card.style.display = matches ? '' : 'none';
            });
        });
    });
</script>
