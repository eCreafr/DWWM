<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP050 FINAL</title>
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
</head>

<body>



    <div class="container d-flex flex-wrap justify-content-center">
        <div class="p-3 m-3 col-12 text-center">
            <?php echo '<a class="btn btn-outline-primary rounded-pill" role="button" href="add.php">AJOUTER UN NOUVEL ARTICLE</a>'; ?></div>


        <?php

        include('db.php');
        // Si tout va bien, on peut continuer :

        // On récupère tout le contenu de la table sport articles
        $sqlQuery = '
            SELECT a.id, a.titre, a.contenu, a.date_publication, r.score, r.lieu
            FROM s2_articles_presse a
            LEFT JOIN s2_resultats_sportifs r ON a.match_id = r.id 
            ORDER BY `a`.`date_publication` 
            DESC; ';
        $newsFraiches = $mysqlClient->prepare($sqlQuery);
        $newsFraiches->execute();
        $news = $newsFraiches->fetchAll();


        // Tronquer fonction
        function truncateString($string, $length = 20)
        {
            if (strlen($string) > $length) {
                return substr($string, 0, $length) . '...';
            }
            return $string;
        }
        // On affiche chaque recette une à une
        foreach ($news as $new) {


        ?>
            <div class="p-3 m-3 col-lg-3">
                <div class="card">
                    <img src="https://picsum.photos/300/150?random=<?php echo $new['id']; ?>" class="card-img-top" alt="<?php echo $new['titre']; ?>">
                    <div class="card-body">
                        <p>
                            <mark> <?php echo $new['date_publication']; ?> </mark>

                            <br>

                        <h5 class="card-title"><?php echo $new['titre']; ?></h5>

                        <?php echo $new['score'] ? "<strong style=\"color:#FF0000\">Score : {$new['score']}</strong>" : "" ?>

                        <?php echo $new['lieu'] ? "<p>à {$new['lieu']}</p>" : "" ?>


                        <p class="card-text"><?php echo truncateString($new['contenu'], 200); ?></p>

                        <a class="btn btn-primary rounded-pill" href="article.php?id=<?php echo $new['id']; ?>">Voir l'article complet</a>

                    </div>
                </div>

                <div class="col-12 mt-2 text-center">
                    <a class="btn btn-outline-success rounded-start-pill" role="button" href="edit.php?id=<?php echo $new['id']; ?>">MODIFIER</a>
                    <a class="btn btn-outline-danger rounded-end-pill" role="button" href="delete.php?id=<?php echo $new['id']; ?>">SUPPRIMER</a>
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