<?php
// ============================================================
// PAGE D'ACCUEIL - INDEX.PHP
// ============================================================
// Cette page affiche la liste de tous les articles sportifs
// sous forme de cartes (cards Bootstrap).
// ============================================================

// Inclusion du fichier de connexion à la base de données WAMPSERVER
include('db.php');

// Inclusion du fichier contenant les fonctions utilitaires
include('functions.php');
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP050 FINAL</title>
    <link href="../../css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* CSS personnalisé pour fixer la hauteur des images des cartes */
        /* Cela évite que les images aient des hauteurs différentes */
        .img-h-fixed {
            height: 100px;
            /* Hauteur fixe de 100 pixels */
            object-fit: cover;
            /* Recadre l'image pour remplir l'espace sans déformation */
            width: 100%;
            /* Largeur à 100% du conteneur */
        }
    </style>
</head>

<body>



    <div class="container d-flex flex-wrap justify-content-center">
        <!-- Bouton pour ajouter un nouvel article -->
        <div class="p-3 m-3 col-12 text-center">
            <?php echo '<a class="btn btn-outline-primary rounded-pill" role="button" href="add.php">AJOUTER UN NOUVEL ARTICLE</a>'; ?>
        </div>


        <?php
        // ============================================================
        // RÉCUPÉRATION DES ARTICLES DEPUIS LA BASE DE DONNÉES
        // ============================================================

        // Requête SQL pour récupérer tous les articles avec leurs infos de match associées
        // On utilise un LEFT JOIN pour récupérer les articles même s'ils n'ont pas de match
        // Cela signifie : "prends tous les articles, et s'il y a un match associé, ajoute ses infos"
        $sqlQuery = '
            SELECT a.id, a.titre, a.contenu, a.date_publication, r.score, r.lieu
            FROM s2_articles_presse a
            LEFT JOIN s2_resultats_sportifs r ON a.match_id = r.id
            ORDER BY `a`.`id` DESC; ';

        // On prépare la requête (étape de sécurité contre les injections SQL)
        $newsFraiches = $mysqlClient->prepare($sqlQuery);

        // On exécute la requête
        $newsFraiches->execute();

        // On récupère tous les résultats sous forme de tableau
        // Chaque ligne devient un élément du tableau $news
        $news = $newsFraiches->fetchAll();



        // ============================================================
        // BOUCLE D'AFFICHAGE DES ARTICLES
        // ============================================================
        // On parcourt chaque article récupéré et on l'affiche dans une carte

        // foreach parcourt chaque élément du tableau $news
        // À chaque tour de boucle, $new contient un article
        foreach ($news as $new) {

        ?>
            <!-- Carte Bootstrap pour afficher un article -->
            <div class="p-3 m-3 col-lg-3">
                <div class="card">

                    <?php
                    // ============================================================
                    // GESTION DE L'IMAGE DE L'ARTICLE
                    // ============================================================

                    // On vérifie si une image existe pour cet article dans le dossier img/
                    // Le nom du fichier doit être : id.jpg (exemple : 5.jpg pour l'article 5)
                    $imagePath = "img/" . $new['id'] . ".jpg";

                    if (file_exists($imagePath)) {
                        // Si l'image existe, on l'utilise
                        $image = "$imagePath";
                    } else {
                        // Sinon, on utilise une image aléatoire depuis le service picsum.photos
                        // Le paramètre random avec l'id garantit que chaque article a toujours la même image
                        $image = "https://picsum.photos/300/150?random=" . $new['id'] . "";
                    }

                    // On affiche l'image avec la balise <img>
                    // htmlspecialchars() protège contre les failles XSS en échappant les caractères spéciaux
                    echo "<img src=\"$image\" class=\"card-img-top img-h-fixed\" alt=\"" . htmlspecialchars($new['titre']) . "\">";

                    ?>

                    <!-- Corps de la carte avec les informations de l'article -->
                    <div class="card-body">
                        <p>
                            <!-- Affichage de la date de publication en surbrillance (balise <mark>) -->
                            <mark> <?php echo $new['date_publication']; ?> </mark>

                            <br>

                        <!-- Affichage du titre de l'article -->
                        <h5 class="card-title"><?php echo $new['titre']; ?></h5>

                        <!-- Affichage conditionnel du score avec opérateur ternaire -->
                        <!-- Syntaxe : condition ? valeur_si_vrai : valeur_si_faux -->
                        <!-- Si $new['score'] existe et n'est pas vide, on affiche le score en rouge -->
                        <?php echo $new['score'] ? "<strong style=\"color:#FF0000\">Score : {$new['score']}</strong>" : "" ?>

                        <!-- Affichage conditionnel du lieu du match -->
                        <?php echo $new['lieu'] ? "<p>à {$new['lieu']}</p>" : "" ?>

                        <!-- Affichage d'un extrait du contenu (tronqué à 200 caractères) -->
                        <!-- La fonction truncateString() est définie dans functions.php -->
                        <p class="card-text"><?php echo truncateString($new['contenu'], 200); ?></p>

                        <!-- Création de l'URL SEO friendly pour cet article -->
                        <!-- Ex: /article/5-psg-gagne-a-domicile-3-1.html -->
                        <?php $url = createArticleUrl($new['id'], $new['titre'], $new['score']); ?>

                        <!-- Lien vers la page complète de l'article -->
                        <!-- Le point avant <?= $url; ?> permet de créer un chemin relatif -->
                        <a class="btn btn-primary rounded-pill" href=".<?= $url; ?>">Voir l'article complet</a>


                    </div>
                </div>

                <!-- Zone des boutons d'action : MODIFIER et SUPPRIMER -->
                <div class=" col-12 mt-2 text-center">

                    <!-- Bouton MODIFIER : redirige vers edit.php avec l'id en paramètre GET -->
                    <!-- Ex: edit.php?id=5 -->
                    <a class="btn btn-outline-success rounded-start-pill" role="button"
                        href="edit.php?id=<?= $new['id']; ?>">MODIFIER</a>

                    <!-- Bouton SUPPRIMER : redirige vers delete.php avec l'id en paramètre GET -->
                    <!-- Ex: delete.php?id=5 -->
                    <a class="btn btn-outline-danger rounded-end-pill" role="button"
                        href="delete.php?id=<?= $new['id']; ?>">SUPPRIMER</a>

                </div>

            </div>

        <?php
        } // Fin de la boucle foreach - on a parcouru tous les articles
        ?>

        <!-- Bouton pour ajouter un nouvel article (en bas de page) -->
        <div class="p-3 m-3 col-12 text-center">
            <?php echo '<a class="btn btn-outline-primary rounded-pill" role="button" href="add.php">AJOUTER UN NOUVEL ARTICLE</a>'; ?>
        </div>

    </div>
</body>

</html>
