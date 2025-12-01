<?php
session_start();
require '../config/config.php';

if (!isset($_GET['id'])) {
    header("Location: http://localhost/savouinos/?page=catalogue");
    exit;
}

$id = intval($_GET['id']);

// Récupération du produit en base
$sql = "SELECT * FROM produit WHERE idproduit = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$produit = $res->fetch_assoc();

if (!$produit) {
    header("Location: http://localhost/savouinos/?page=catalogue");
    exit;
}

// Préparation du tableau
$item = [
    'name' => $produit['nom'],
    'description' => $produit['description'],
    'image' => "../public/asset/img/" . $produit['image'],
    'prix' => floatval($produit['prix']),
    'quantite' => 1
];

// Initialiser panier si vide
if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

// Si produit déjà dans le panier → augmenter quantité
if (isset($_SESSION['panier'][$id])) {
    $_SESSION['panier'][$id]['quantite']++;
} else {
    $_SESSION['panier'][$id] = $item;
}

// Rediriger vers le panier
header("Location: http://localhost/savouinos/?page=panier");
exit;
