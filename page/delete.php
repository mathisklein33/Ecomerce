<?php
require '../public/config/config.php';

// Vérification de l'ID
if (!isset($_GET['idproduit']) || !is_numeric($_GET['idproduit'])) {
    die("ID invalide");
}

$id = (int) $_GET['idproduit'];

// Vérifier la connexion
if ($conn->connect_error) {
    die('Erreur : ' . $conn->connect_error);
}

// Requête SQL DELETE
$sql = "DELETE FROM produit WHERE idproduit = $id";

if (mysqli_query($conn, $sql)) {
    mysqli_close($conn);
    header('Location: admin-produit.php');
    exit;
} else {
    echo "Erreur delete : " . mysqli_error($conn);
}
?>