<?php

if (!isset($_GET['idproduit']) || empty($_GET['idproduit'])) {
    die("ID produit manquant");
}

$id = (int) $_GET['idproduit'];

// Récupération du produit
$sql = "SELECT * FROM produit WHERE idproduit = $id";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    die("Produit introuvable");
}

$produit = mysqli_fetch_assoc($result);


// ---- Sauvegarde des modifications ----
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nom = mysqli_real_escape_string($conn, $_POST['nom']);
    $prix = (int) $_POST['prix'];
    $stock = (int) $_POST['stock'];
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $update = "
        UPDATE produit 
        SET nom='$nom',
            prix='$prix',
            stock='$stock',
            description='$description'
        WHERE idproduit=$id
    ";

    if (mysqli_query($conn, $update)) {
        header("Location: http://localhost/savouinos/?page=admin/produit&msg=updated");
        exit();
    } else {
        echo "Erreur : " . mysqli_error($conn);
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modifier produit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../public/asset/CSS/style.css">

</head>
<body>

</body>
</html>

<div class="container form-container">
    <form method="POST" class="form mx-6" >
        <h2 class="mt-3">Modifier le produit : <?= htmlspecialchars($produit['nom']) ?></h2>

        <div class="mb-3 p-4">
            <label>Nom :</label>
            <input type="text" name="nom" class="form-control " value="<?= htmlspecialchars($produit['nom']) ?>">
        </div>

        <div class="mb-3 p-4">
            <label>Prix :</label>
            <input type="number" name="prix" class="form-control" value="<?= $produit['prix'] ?>">
        </div>

        <div class="mb-3 p-4">
            <label>Stock :</label>
            <input type="number" name="stock" class="form-control" value="<?= $produit['stock'] ?>">
        </div>

        <div class="mb-3 p-4">
            <label>Description :</label>
            <textarea name="description" class="form-control"><?= htmlspecialchars($produit['description']) ?></textarea>
        </div>

        <button type="submit" class="btn btn-submit">Enregistrer</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

