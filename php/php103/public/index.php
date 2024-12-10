<?php

session_start(); // Démarrage de la session

$title = "Le générateur de punition by Bart"; // Titre par défaut
include('../common/variables.php');


// Gestion de l'affichage de la page demandée
if (isset($_GET['page']) && array_key_exists($_GET['page'], $whitelist)) {

    $title = $whitelist[$_GET['page']]; // Titre spécifique à la page
    include('../common/header.php');
    include("../pages/" . $_GET['page'] . '.php');
} elseif (!isset($_GET['page'])) {
    include('../common/header.php');
    include('../pages/home.php');
} else {
    include('../common/header.php');
    echo "Vous êtes perdu ?";
}



// Affichage de la partie basse de votre site, commun à l'ensemble de votre site
include('../common/footer.php');
