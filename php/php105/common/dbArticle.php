 <?php


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
    }
    ?>