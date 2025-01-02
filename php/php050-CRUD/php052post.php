<?php


include('php050connect.php');

$postData = $_POST;

if (!isset($postData['id']) || !is_numeric($postData['id'])) {
    echo 'Il faut un identifiant valide pour supprimer un article.';
    return;
}

$deleteArticleStatement = $mysqlClient->prepare('DELETE FROM s2_articles_presse WHERE id = :id');
$deleteArticleStatement->execute([
    'id' => (int)$postData['id'],
]);

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <p>c'est supprimé </p> <br>

    <a class="btn btn-primary" role="button" href="php050tp2.php">RETOUR</a>
</body>

</html>