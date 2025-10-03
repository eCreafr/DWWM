 <?php
// ============================================================
// PAGE D'AFFICHAGE D'UN ARTICLE COMPLET - ARTICLE.PHP
// ============================================================
// Cette page affiche le détail complet d'un article sportif
// en récupérant son ID depuis l'URL (paramètre GET).
// ============================================================

// Inclusion de la connexion à la base de données
include('db.php');

// Inclusion des fonctions utilitaires
include('functions.php');


// ============================================================
// VÉRIFICATION ET RÉCUPÉRATION DE L'ID DE L'ARTICLE
// ============================================================

// isset() vérifie si la variable existe
// is_numeric() vérifie si c'est un nombre
if (isset($_GET['id']) && is_numeric($_GET['id'])) {

    // On récupère l'ID de l'article depuis l'URL
    // Ex: article.php?id=5 → $articleId = 5
    $articleId = $_GET['id'];

    // ============================================================
    // RÉCUPÉRATION DE L'ARTICLE DEPUIS LA BASE DE DONNÉES
    // ============================================================

    // Requête SQL avec LEFT JOIN pour récupérer l'article et ses infos de match
    // LEFT JOIN = on récupère l'article même s'il n'a pas de match associé
    $sqlQuery = '
        SELECT a.id, a.titre, a.contenu, a.date_publication, r.score, r.lieu
        FROM s2_articles_presse a
        LEFT JOIN s2_resultats_sportifs r ON a.match_id = r.id
        WHERE a.id = :id';

    // Préparation de la requête (protection contre les injections SQL)
    $statement = $mysqlClient->prepare($sqlQuery);

    // On lie le paramètre :id à la valeur de $articleId
    // PDO::PARAM_INT indique que c'est un nombre entier
    $statement->bindParam(':id', $articleId, PDO::PARAM_INT);

    // Exécution de la requête
    $statement->execute();

    // Récupération de l'article (une seule ligne)
    // fetch() retourne un tableau associatif avec les données de l'article
    $article = $statement->fetch();

    // Création d'un extrait du contenu (50 premiers caractères)
    // Cet extrait pourrait être utilisé pour le SEO ou les métadonnées
    $truncatedContent = substr($article['contenu'], 0, 50) . '';

    ?>


     <!DOCTYPE html>
     <html lang="fr">

     <head>
         <meta charset="UTF-8">
         <meta name="viewport" content="width=device-width, initial-scale=1.0">

         <!-- Le titre de la page affiche le score (si disponible) et le titre de l'article -->
         <title>l'équipe | <?php
                            echo $article['score'] ? "{$article['score']} " : "";
                            echo $article['titre'];
                            ?></title>

         <link href="../../../css/bootstrap.min.css" rel="stylesheet">
     </head>

     <body>

         <div class="container text-center d-flex  flex-wrap justify-content-center">

         <?php
        // ============================================================
        // AFFICHAGE DE L'ARTICLE
        // ============================================================

            // Si l'article existe (si fetch() a retourné des données)
            if ($article) {

                // Ouverture d'une carte Bootstrap pour afficher l'article
                echo "<div class='card col-9 m-5 p-3'>";

                // ============================================================
                // GESTION DE L'IMAGE DE L'ARTICLE
                // ============================================================

                // Vérification si une image existe pour cet article dans le dossier img/
                $imagePath = "img/" . $article['id'] . ".jpg";

                if (file_exists($imagePath)) {
                    // Si l'image existe, on l'utilise
                    // ATTENTION : on ajoute ../ car article.php est dans un sous-dossier virtuel
                    $image = "../$imagePath";
                } else {
                    // Sinon, on utilise une image aléatoire depuis picsum.photos
                    $image = "https://picsum.photos/800/150";
                }

                // Affichage de l'image avec htmlspecialchars() pour la sécurité
                echo "<img src=\"$image\" class=\"img-fluid rounded-top mb-2\" alt=\"" . htmlspecialchars($article['titre']) . "\">";


                // Affichage du titre principal de l'article
                echo "<h1>{$article['titre']}</h1>";

                // Affichage de la date de publication
                echo "<p>Date: {$article['date_publication']}</p>";

                // Affichage conditionnel du score (opérateur ternaire)
                echo $article['score'] ? "<strong style=\"color:#FF0000\">score : {$article['score']}</strong>" : "";

                // Affichage conditionnel du lieu du match
                echo $article['lieu'] ? "<p>lieu : {$article['lieu']}</p>" : "";

                // Affichage du contenu complet de l'article
                echo "<p>{$article['contenu']}</p>";

                // Fermeture de la carte
                echo "</div>";

            } else {
                // Si l'article n'existe pas (aucune ligne retournée par la requête)
                echo "<p>Article non trouvé.</p>";
            }

        } else {
            // Si l'ID est manquant ou invalide dans l'URL
            echo "<div class='card m-5 p-5'><p>Identifiant d'article manquant ou invalide.</p></div>";
        }

            ?>

         <!-- Zone des boutons -->
         <div class="col-12">

             <!-- ============================================================ -->
             <!-- BOUTON DE PARTAGE (utilise l'API Web Share) -->
             <!-- ============================================================ -->
             <!-- Cette API ne fonctionne qu'en HTTPS ! -->
             <button
                 id="shareButton"
                 class="btn btn-primary share-button"
                 data-title="<?= $article['titre'] ? "{$article['titre']} " : ""; ?>"
                 data-text="<?= $article['titre'] ? "{$article['titre']} " : ""; ?>"
                 data-url="article.php?id=<?= ($_GET['id']); ?>"><img src="../../img/share.svg" alt="partager <?= $article['titre']; ?>" width="24px">
             </button>

             <!-- Bouton de retour à la page d'accueil -->
             <a class="btn btn-primary" role="button" href="../">RETOUR</a>

             <!-- Zone d'alerte pour le partage (affichée par JavaScript) -->
             <div id="shareAlert" class="alert"></div>
         </div>


         </div>

         <!-- Script JavaScript pour gérer le partage -->
         <script src="../../js/share.js"></script>
     </body>

     </html>
