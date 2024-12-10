<?php

include('php050connect.php');

/**
 * On ne traite pas les super globales provenant de l'utilisateur directement,
 * ces données doivent être testées et vérifiées.
 */
$getData = $_GET;

if (!isset($getData['id']) || !is_numeric($getData['id'])) {
    echo ('Il faut un identifiant de news pour la modifier. http://lateste.fr/git/php/php053.php?id=5 ');
    return;
}

$retrieveArticleStatement = $mysqlClient->prepare('SELECT titre, contenu FROM s2_articles_presse WHERE id = :id');
$retrieveArticleStatement->execute([
    'id' => (int)$getData['id'],
]);
$article = $retrieveArticleStatement->fetch(PDO::FETCH_ASSOC);

// si la recette n'est pas trouvée, renvoyer un message d'erreur
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edition d'article</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="d-flex flex-column min-vh-100">
    <div class="container">

        <h1>Mettre à jour <?php echo ($article['titre']); ?></h1>
        <form action="php053post.php" method="POST">

            <div class="mb-3 visually-hidden">
                <label for="id" class="form-label">Identifiant de la news</label>
                <input type="hidden" class="form-control" id="id" name="id" value="<?php echo ($getData['id']); ?>">
            </div>

            <div class="mb-3">
                <label for="titre" class="form-label">Titre </label>
                <input type="text" class="form-control" id="titre" name="titre" aria-describedby="titre-help" value="<?php echo ($article['titre']); ?>">
                <div id="titre-help" class="form-text">Choisissez un titre percutant !</div>
            </div>

            <div class="mb-3">
                <label for="contenu" class="form-label">Contenu</label>
                <textarea class="form-control" placeholder="" id="contenu" name="contenu">
                    <?php echo $article['contenu']; ?>
                </textarea>
            </div>

            <button type="submit" class="btn btn-primary">Envoyer</button>

        </form>
        <br />
    </div>


</body>

</html>