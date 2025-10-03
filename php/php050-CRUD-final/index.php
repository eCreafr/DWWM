<?php
include('db.php');
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
        /* pour fixer la hauteur des vignettes des cards */
        .img-h-fixed {
            height: 100px;
            object-fit: cover;
            width: 100%;
        }
    </style>
</head>

<body>



    <div class="container d-flex flex-wrap justify-content-center">
        <div class="p-3 m-3 col-12 text-center">
            <?php echo '<a class="btn btn-outline-primary rounded-pill" role="button" href="add.php">AJOUTER UN NOUVEL ARTICLE</a>'; ?></div>


        <?php

        // On récupère tout le contenu de la table sport articles
        $sqlQuery = '
            SELECT a.id, a.titre, a.contenu, a.date_publication, r.score, r.lieu
            FROM s2_articles_presse a
            LEFT JOIN s2_resultats_sportifs r ON a.match_id = r.id 
            ORDER BY `a`.`id` DESC; ';
        $newsFraiches = $mysqlClient->prepare($sqlQuery);
        $newsFraiches->execute();
        $news = $newsFraiches->fetchAll();



        // On affiche chaque news sportives une à une
        foreach ($news as $new) {

        ?>
            <div class="p-3 m-3 col-lg-3">
                <div class="card">

                    <?php
                    // Vérifie si une image existe pour cet article dans le repertoire img
                    $imagePath = "img/" . $new['id'] . ".jpg";
                    if (file_exists($imagePath)) {
                        $image = "$imagePath";
                    } else {
                        //sinon on affiche une image aléatoire
                        $image = "https://picsum.photos/300/150?random=" . $new['id'] . "";
                    }

                    echo "<img src=\"$image\" class=\"card-img-top img-h-fixed\" alt=\"" . htmlspecialchars($new['titre']) . "\">";

                    ?>

                    <div class="card-body">
                        <p>
                            <mark> <?php echo $new['date_publication']; ?> </mark>

                            <br>

                        <h5 class="card-title"><?php echo $new['titre']; ?></h5>

                        <?php echo $new['score'] ? "<strong style=\"color:#FF0000\">Score : {$new['score']}</strong>" : "" ?>

                        <?php echo $new['lieu'] ? "<p>à {$new['lieu']}</p>" : "" ?>


                        <p class="card-text"><?php echo truncateString($new['contenu'], 200); ?></p>

                        <?php $url = createArticleUrl($new['id'], $new['titre'], $new['score']); ?>

                        <a class="btn btn-primary rounded-pill" href=".<?= $url; ?>">Voir l'article complet</a>


                    </div>
                </div>

                <div class=" col-12 mt-2 text-center">

                    <a class="btn btn-outline-success rounded-start-pill" role="button"
                        href="edit.php?id=<?= $new['id']; ?>">MODIFIER</a>

                    <a class="btn btn-outline-danger rounded-end-pill" role="button"
                        href="delete.php?id=<?= $new['id']; ?>">SUPPRIMER</a>

                </div>

            </div>

        <?php
        }
        ?>
        <div class="p-3 m-3 col-12 text-center">
            <?php echo '<a class="btn btn-outline-primary rounded-pill" role="button" href="add.php">AJOUTER UN NOUVEL ARTICLE</a>'; ?>
        </div>

    </div>
</body>

</html>