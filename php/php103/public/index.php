<?php

session_start(); // Démarrage de la session

$title = "Le générateur de punition by Bart"; // Titre par défaut

include('../common/variables.php');
include('../common/header.php');

// Gestion de l'affichage de la page demandée
if (isset($_GET['page']) && array_key_exists($_GET['page'], $whitelist)) {
    $title = $whitelist[$_GET['page']]; // Titre spécifique à la page
    include("../pages/" . $_GET['page'] . '.php');
} elseif (!isset($_GET['page'])) {
    include('../pages/home.php');
} else {
    echo "Vous êtes perdu ?";
}



// Affichage de la partie basse de votre site, commun à l'ensemble de votre site
include('../common/footer.php');
