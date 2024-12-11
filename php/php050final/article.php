 <?php
    include('db.php');

    // Check if an ID is provided
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $articleId = $_GET['id'];

        // Fetch the article by ID
        $sqlQuery = '
            SELECT a.titre, a.contenu, a.date_publication, r.score, r.lieu
            FROM s2_articles_presse a
            LEFT JOIN s2_resultats_sportifs r ON a.match_id = r.id 
            WHERE a.id = :id';
        $statement = $mysqlClient->prepare($sqlQuery);
        $statement->bindParam(':id', $articleId, PDO::PARAM_INT);
        $statement->execute();
        $article = $statement->fetch();
        $truncatedContent = substr($article['contenu'], 0, 50) . '';

    ?>


     <!DOCTYPE html>
     <html lang="fr">

     <head>
         <meta charset="UTF-8">
         <meta name="viewport" content="width=device-width, initial-scale=1.0">
         <title>l'équipe | <?php
                            echo $article['score'] ? "{$article['score']} " : "";
                            echo $article['titre'];
                            ?></title>
         <link href="../../css/bootstrap.min.css" rel="stylesheet">
     </head>

     <body>

         <div class="container text-center d-flex  flex-wrap justify-content-center">

         <?php

            if ($article) {
                echo "<div class='card col-9 m-5 p-3'>";
                echo "<img src=\"https://picsum.photos/800/150\" class=\"img-fluid rounded-top mb-2\" alt=\"\">";
                echo "<h1>{$article['titre']}</h1>";
                echo "<p>Date: {$article['date_publication']}</p>";
                echo $article['score'] ? "<strong style=\"color:#FF0000\">score : {$article['score']}</strong>" : "";
                echo $article['lieu'] ? "<p>lieu : {$article['lieu']}</p>" : "";
                echo "<p>{$article['contenu']}</p>";
                echo "</div>";
            } else {
                echo "<p>Article non trouvé.</p>";
            }
        } else {
            echo "<div class='card m-5 p-5'><p>Identifiant d'article manquant ou invalide.</p></div>";
        }

            ?>

         <div class="col-12">
             <!-- l'api share ne fonctionne qu'en httpS !  -->
             <button
                 id="shareButton"
                 class="btn btn-primary share-button"
                 data-title="<?php echo $article['titre'] ? "{$article['titre']} " : ""; ?>"
                 data-text="<?php echo $article['titre'] ? "{$article['titre']} " : ""; ?>"
                 data-url="article.php?id=<?php echo ($_GET['id']); ?>"><img src="../img/share.svg" alt="partager <?php echo $article['titre']; ?>" width="24px">
             </button>

             <a class="btn btn-primary" role="button" href="./">RETOUR</a>

             <div id="shareAlert" class="alert"></div>
         </div>


         </div>
         <script src="../js/share.js"></script>
     </body>

     </html>