<?php
session_start();

// Détruire toutes les variables de session
$_SESSION = [];

// Détruire la session
session_destroy();

// Redirection vers l'accueil
header("Location: http://localhost/savouinos/index.php");
exit();
?>

