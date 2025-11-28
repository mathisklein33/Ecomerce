<?php
include 'public/includes/header.php';
include 'public/config/config.php';


$routes = [
        'base' => __DIR__ .'/page/accueil.php',
        'login' => __DIR__ . '/page/connection-from.php',
        'inscription' => __DIR__ . '/page/inscription.php',
        'produits' => __DIR__ . '/page/dettaille-produit.php',
        'commande' => __DIR__ . '/page/commande.php',
        'admin/produit' => __DIR__ . '/page/admin-produit.php',
        'admin/command' => __DIR__ . '/page/admin-command.php',
        'produit/creation' => __DIR__ . '/page/produit-creation.php',
        'produit/modifier' => __DIR__ . '/page/modifier.php',
        'produit/supprimer' => __DIR__ . '/page/delete.php',
        'user' => __DIR__ . '/page/user.php',
    'panier' => __DIR__ . '/page/panier.php',

    ];

$page = $_GET['page'] ?? 'base';

$view = $routes[$page] ?? __DIR__ . '/page/404.php';

include_once $view;

include 'public/includes/footer.php';
?>

