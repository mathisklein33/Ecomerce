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


<h2>Modifier le produit : <?= htmlspecialchars($produit['nom']) ?></h2>

<form method="POST">

    <label>Nom :</label><br>
    <input type="text" name="nom" value="<?= htmlspecialchars($produit['nom']) ?>"><br><br>

    <label>Prix :</label><br>
    <input type="number" name="prix" value="<?= $produit['prix'] ?>"><br><br>

    <label>Stock :</label><br>
    <input type="number" name="stock" value="<?= $produit['stock'] ?>"><br><br>

    <label>Description :</label><br>
    <textarea name="description"><?= htmlspecialchars($produit['description']) ?></textarea><br><br>

    <button type="submit">Enregistrer</button>
</form>
