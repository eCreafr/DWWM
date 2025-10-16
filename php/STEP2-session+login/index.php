<!--
    Page d'accueil - index.php
    ===========================

    C'est la page principale du site. Elle affiche :
    - Un message de bienvenue personnalisé si l'utilisateur est connecté
    - Des cartes d'actualités (visibles par tous)
    - Un formulaire de connexion (uniquement pour les visiteurs non connectés)
    - Plus de contenu réservé aux abonnés connectés

    📚 Concepts abordés :
    - Affichage conditionnel avec isset() et if/else
    - Sessions PHP pour gérer l'état de connexion
    - Inclusion de fichiers partiels (header, footer, login)
    - Structure HTML responsive avec Bootstrap
-->

<!DOCTYPE html>
<html lang="fr">

<head>
    <?php
    // Inclusion du fichier head.php qui contient :
    // - Le démarrage de la session
    // - La connexion à la base de données
    // - Les imports CSS
    require_once(__DIR__ . '/head.php');
    ?>
    <title>structure site PHP par section / bloc</title>
</head>

<body>
    <div style="background: #F8F9F9;" class="container-fluid d-flex flex-column justify-content-center ">

        <!-- Inclusion du header (logo + menu de navigation) -->
        <?php require_once(__DIR__ . '/header.php'); ?>

        <!-- === CORPS DE LA PAGE === -->

        <div class="container text-center">
            <!--
                Affichage conditionnel du prénom de l'utilisateur
                Si $_SESSION['LOGGED_USER'] existe, on affiche le prénom
            -->
            <h1><?php if (isset($_SESSION['LOGGED_USER'])) : echo $_SESSION['LOGGED_USER']['prenom'];
                endif; ?>bienvenue sur le site de l'équipe</h1>
            <h2>à la une :</h2>
        </div>

        <!--
            Grille de cartes d'actualités
            Classes Bootstrap utilisées :
            - d-flex : Active Flexbox
            - flex-wrap : Permet le retour à la ligne
            - gap-5 : Espace entre les éléments
            - justify-content-center : Centre les éléments
        -->
        <div class="container d-flex flex-wrap gap-5 justify-content-center">
            <!--
                Cartes d'aperçu accessibles à TOUS les visiteurs
                col-12 : Pleine largeur sur mobile
                col-md-4 : 3 colonnes sur écrans moyens et grands
            -->
            <div class="card col-12 col-md-4 p-3"><img src="img/102-card.jpeg" class="img-fluid" alt="">Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolor modi similique quia numquam ad, facilis perspiciatis possimus quam laboriosam dolorem unde voluptatem minus nisi quas quidem officia quis expedita omnis.</div>
            <div class="card col-12 col-md-4 p-3"><img src="img/102-card.jpeg" class="img-fluid" alt="">Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolor modi similique quia numquam ad, facilis perspiciatis possimus quam laboriosam dolorem unde voluptatem minus nisi quas quidem officia quis expedita omnis.</div>
            <div class="card col-12 col-md-4 p-3"><img src="img/102-card.jpeg" class="img-fluid" alt="">Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolor modi similique quia numquam ad, facilis perspiciatis possimus quam laboriosam dolorem unde voluptatem minus nisi quas quidem officia quis expedita omnis.</div>

            <!--
                Inclusion du formulaire de connexion
                Ce fichier affiche le formulaire OU une modale de bienvenue selon l'état de connexion
            -->
            <?php require_once(__DIR__ . '/login.php');  ?>

            <!--
                === CONTENU RÉSERVÉ AUX ABONNÉS ===

                Ce bloc n'est affiché QUE si l'utilisateur est connecté.
                C'est une technique simple de restriction de contenu.
            -->
            <?php if (isset($_SESSION['LOGGED_USER'])) :  ?>

                <!-- 6 cartes supplémentaires visibles uniquement par les abonnés -->
                <div class="card col-12 col-md-4 p-3"><img src="img/102-card.jpeg" class="img-fluid" alt="">Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolor modi similique quia numquam ad, facilis perspiciatis possimus quam laboriosam dolorem unde voluptatem minus nisi quas quidem officia quis expedita omnis.</div>
                <div class="card col-12 col-md-4 p-3"><img src="img/102-card.jpeg" class="img-fluid" alt="">Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolor modi similique quia numquam ad, facilis perspiciatis possimus quam laboriosam dolorem unde voluptatem minus nisi quas quidem officia quis expedita omnis.</div>
                <div class="card col-12 col-md-4 p-3"><img src="img/102-card.jpeg" class="img-fluid" alt="">Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolor modi similique quia numquam ad, facilis perspiciatis possimus quam laboriosam dolorem unde voluptatem minus nisi quas quidem officia quis expedita omnis.</div>
                <div class="card col-12 col-md-4 p-3"><img src="img/102-card.jpeg" class="img-fluid" alt="">Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolor modi similique quia numquam ad, facilis perspiciatis possimus quam laboriosam dolorem unde voluptatem minus nisi quas quidem officia quis expedita omnis.</div>
                <div class="card col-12 col-md-4 p-3"><img src="img/102-card.jpeg" class="img-fluid" alt="">Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolor modi similique quia numquam ad, facilis perspiciatis possimus quam laboriosam dolorem unde voluptatem minus nisi quas quidem officia quis expedita omnis.</div>
                <div class="card col-12 col-md-4 p-3"><img src="img/102-card.jpeg" class="img-fluid" alt="">Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolor modi similique quia numquam ad, facilis perspiciatis possimus quam laboriosam dolorem unde voluptatem minus nisi quas quidem officia quis expedita omnis.</div>

            <?php endif;  ?>
        </div>

        <!-- Inclusion du footer (bas de page) -->
        <?php require_once(__DIR__ . '/footer.php'); ?>
    </div>

</body>

</html>
