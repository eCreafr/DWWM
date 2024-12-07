<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
        rel="stylesheet">
</head>

<body>


    <div class="container d-flex flex-wrap">


        <?php

        include('php050connect.php');
        // Si tout va bien, on peut continuer :

        // On récupère tout le contenu de la table sport articles
        $sqlQuery = '
SELECT a.id, a.titre, a.contenu, a.date_publication, r.score, r.lieu
FROM s2_articles_presse a
JOIN s2_resultats_sportifs r ON a.match_id = r.id 
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
            <div class="card p-3 m-3 col-lg-3">
                <p>-<?php echo $new['date_publication']; ?> <strong><?php echo $new['titre']; ?> :</strong><strong style="color:#FF0000"> <?php echo $new['score']; ?></strong> (lieu : <?php echo $new['lieu']; ?>)
                    <br><?php echo truncateString($new['contenu'], 99); ?>(...) <a href="php050tp2article.php?id=<?php echo $new['id']; ?>">lire la suite</a>

                </p><br><br>
                <div>
                    If Admin : <br><a class="btn btn-outline-success" role="button" href="http://lateste.fr/git/php/php053.php?id=<?php echo $new['id']; ?>">MODIFIER</a> 
                    <a class="btn btn-outline-danger" role="button" href="http://lateste.fr/git/php/php052.php?id=<?php echo $new['id']; ?>">SUPPRIMER</a> <br>

                </div>

            </div>
        <?php
        }
        ?>
        <div class="card p-3 m-3 col-12">
            <?php echo '<a class="btn btn-outline-primary" role="button" href="php051.php">AJOUTER UN NOUVEL ARTICLE</a>'; ?></div>
    </div>
</body>

</html>