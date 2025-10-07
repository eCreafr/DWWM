<?php

//index = notre point d'entrée unique

session_start(); // Démarrage de la session
require_once('../common/db.php');
require_once('../common/config.php');
require_once('../common/functions.php');
require_once('../common/variables.php');


$title = "L'Actu avec Sport 2000"; // Titre par défaut
$metadesc = "L'Actu avec Sport 2000 : c'est les meilleurs journalistes sportifs spécialisés qui..."; // meta description par défaut


// Gestion de l'affichage de la page demandée mixe rewrite htaccess + méthode get

// on gère d'abord les articles
if (isset($_GET['page']) && $_GET['page'] === 'article' && isset($_GET['id'])) {
    include('../common/dbArticle.php');
    $title = $article['titre'];
    $metadesc = $article['titre'] . ", " . truncateString($article['contenu'], 80);
    include('../common/header.php');
    include("../pages/article.php");

    //puis les pages whitelistées
} elseif (isset($_GET['page']) && array_key_exists($_GET['page'], $whitelist)) {
    $title = $whitelist[$_GET['page']];
    include('../common/header.php');
    include("../pages/" . $_GET['page'] . '.php');

    //si rien n'est demandé on affiche par defaut la page d'accueil
} elseif (!isset($_GET['page'])) {
    include('../common/header.php');
    include('../pages/home.php');

    // et enfin, si on demande autre chose qu'article, pages referencées, ou du vide, on est sur erreur 404 :
} else {
    include('../common/header.php');
    echo "<div class=\"alert alert-danger my-5\" role=\"alert\">euh... Vous êtes perdu ?</div>";
}



// Affichage de la partie basse du site "footer", commun à tout le site
include('../common/footer.php');
