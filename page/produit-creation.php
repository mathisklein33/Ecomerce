<?php


/* ---------------------------------------------
     Vérification connexion + rôle admin
---------------------------------------------*/
if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
    header("Location: index.php");
    exit;
}
/* ---------------------------------------------
     Traitement du formulaire
--------------------------------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nom         = $_POST['nom'];
    $description = $_POST['description'];
    $prix        = $_POST['prix'];
    $stock       = $_POST['stock'];

    $id_user     = $_SESSION['user_id'];
    $id_role     = $_SESSION['role_id']; // normalement 2 (admin)

    // Gestion de l'image fichier
    $imageName = null;

    if (!empty($_FILES['image']['name'])) {

        $uploadDir = "../public/asset/img/";
        $imageName = time() . "_" . basename($_FILES['image']['name']); // nom du fichier
        $target    = $uploadDir . $imageName;

        // Déplacer l'image dans le dossier /img/
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            echo "<p style='color:red;'>Erreur lors de l'upload de l'image.</p>";
            exit();
        }
    }

    // Date actuelle
    $date = date('Y-m-d');


       //Insertion dans la base de donnée

    $stmt = $conn->prepare("
        INSERT INTO produit (nom, description, prix, stock, image, date, user_iduser, user_role_idrole)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
            "ssisssii",
            $nom,
            $description,
            $prix,
            $stock,
            $imageName,  // <-- on stocke UNIQUEMENT "monimage.jpg"
            $date,
            $id_user,
            $id_role
    );

    if ($stmt->execute()) {
        echo "<p style='color:green;'>Produit ajouté avec succès !</p>";
    } else {
        echo "<p style='color:red;'>Erreur : " . $conn->error . "</p>";
    }
}
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="style-produit.css">

<div class="page-container">

    <div class="product-card shadow">
        <h2 class="text-center mb-4">Créer un produit</h2>

        <form action="" method="POST" enctype="multipart/form-data">

            <div class="mb-3">
                <label class="form-label">Nom du produit</label>
                <input type="text" name="nom" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3" required></textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Prix (€)</label>
                    <input type="number" name="prix" step="0.01" class="form-control" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Stock</label>
                    <input type="number" name="stock" class="form-control" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Image</label>
                <input type="file" name="image" accept="image/*" class="form-control">
            </div>

            <button type="submit" class="btn-submit w-100 mt-3">Créer le produit</button>

        </form>
    </div>

</div>
