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


<h2>Créer un produit</h2>

<form action="" method="POST" enctype="multipart/form-data">

    <label>Nom du produit :</label><br>
    <input type="text" name="nom" required><br><br>

    <label>Description :</label><br>
    <textarea name="description" required></textarea><br><br>

    <label>Prix (€) :</label><br>
    <input type="number" name="prix" step="0.01" required><br><br>

    <label>Stock :</label><br>
    <input type="number" name="stock" required><br><br>

    <label>Image :</label><br>
    <input type="file" name="image" accept="image/*"><br><br>

    <button type="submit">Créer le produit</button>

</form>
