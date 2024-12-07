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

    <div class="container text-center">

        <?php
        include('php050connect.php');

        // Check if an ID is provided
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $articleId = $_GET['id'];

            // Fetch the article by ID
            $sqlQuery = '
    SELECT a.titre, a.contenu, a.date_publication, r.score, r.lieu
    FROM s2_articles_presse a
    JOIN s2_resultats_sportifs r ON a.match_id = r.id 
    WHERE a.id = :id';
            $statement = $mysqlClient->prepare($sqlQuery);
            $statement->bindParam(':id', $articleId, PDO::PARAM_INT);
            $statement->execute();
            $article = $statement->fetch();

            if ($article) {
                echo "<div class='card m-5 p-5'>";
                echo "<h1>{$article['titre']}</h1>";
                echo "<p>Date: {$article['date_publication']}</p>";
                echo "<p>Score: {$article['score']}</p>";
                echo "<p>Lieu: {$article['lieu']}</p>";
                echo "<p>{$article['contenu']}</p>";
                echo "</div>";
            } else {
                echo "<p>Article non trouvé.</p>";
            }
        } else {
            echo "<p>Identifiant d'article manquant ou invalide.</p>";
        } ?>

        <a class="btn btn-primary" role="button" href="php050tp2.php">RETOUR</a>
    </div>
</body>

</html>