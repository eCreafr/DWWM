 <?php
    include('db.php');
    include('functions.php');


    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $articleId = $_GET['id'];

        // Fetch the article by ID
        $sqlQuery = '
            SELECT a.id, a.titre, a.contenu, a.date_publication, r.score, r.lieu
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
         <link href="../../../css/bootstrap.min.css" rel="stylesheet">
     </head>

     <body>

         <div class="container text-center d-flex  flex-wrap justify-content-center">

         <?php

            if ($article) {
                echo "<div class='card col-9 m-5 p-3'>";

                // Vérifie si une image existe pour cet article dans le repertoire img
                $imagePath = "img/" . $article['id'] . ".jpg";
                if (file_exists($imagePath)) {
                    $image = "../$imagePath";
                    // j'ai du ajouter ../ car le fichier article.php est dans un sous dossier virtuel
                } else {
                    //sinon on affiche une image aléatoire
                    $image = "https://picsum.photos/800/150";
                }

                echo "<img src=\"$image\" class=\"img-fluid rounded-top mb-2\" alt=\"" . htmlspecialchars($article['titre']) . "\">";


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
                 data-title="<?= $article['titre'] ? "{$article['titre']} " : ""; ?>"
                 data-text="<?= $article['titre'] ? "{$article['titre']} " : ""; ?>"
                 data-url="article.php?id=<?= ($_GET['id']); ?>"><img src="../../img/share.svg" alt="partager <?= $article['titre']; ?>" width="24px">
             </button>

             <a class="btn btn-primary" role="button" href="../">RETOUR</a>

             <div id="shareAlert" class="alert"></div>
         </div>


         </div>
         <script src="../../js/share.js"></script>
     </body>

     </html>