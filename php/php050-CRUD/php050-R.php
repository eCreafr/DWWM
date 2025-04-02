    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>php050 READ</title>
    </head>

    <body>

        <?php

        // on se connecte à la BDD en incluant le fichier de connexion :
        include('php050-connect.php');
        // Si tout va bien, on peut continuer :

        // On récupère tout le contenu de la table sport articles avec une requête SQL :
        $sqlQuery = '
            SELECT a.id, a.titre, a.contenu, a.date_publication, r.score, r.lieu
            FROM s2_articles_presse a
            LEFT JOIN s2_resultats_sportifs r ON a.match_id = r.id 
            ORDER BY `a`.`date_publication` 
            DESC;';
        $newsFraiches = $mysqlClient->prepare($sqlQuery);
        $newsFraiches->execute();
        $news = $newsFraiches->fetchAll();


        // une fonction pour tronquer les chaînes de caractères trop longues des articles et n'afficher que les premieres lignes :
        function truncateString($string, $length = 20)
        {
            if (strlen($string) > $length) {
                return substr($string, 0, $length) . ' (...)';
            }
            return $string;
        }

        echo '<h1>étape 2 / on affiche une boucle de news contenues dans la BDD avec un SQL qui LEFT JOIN</h1><hr>';
        // On affiche chaque article de sport un à un
        foreach ($news as $new) {

        ?>
            id unique : <?php echo $new['id']; ?>
            <h2><?php echo truncateString($new['titre'], 20); ?> :</h2><strong style="color:#FF0000"> <?php echo $new['score']; ?></strong> (lieu : <?php echo $new['lieu']; ?>)
            <?php echo truncateString($new['contenu'], 100); ?>
            <p>-<?php echo $new['date_publication']; ?>
            </p><br><br><br><br>

            <hr>
        <?php
        }
        ?>


    </body>

    </html>