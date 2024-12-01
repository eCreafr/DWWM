<?php

include('php050connect.php');
/**
 * On ne traite pas les super globales provenant de l'utilisateur directement,
 * ces données doivent être testées et vérifiées.
 */
$postData = $_POST;

if (
    !isset($postData['id'])
    || !is_numeric($postData['id'])
    || empty($postData['titre'])
    || empty($postData['contenu'])
    || trim(strip_tags($postData['titre'])) === ''
    || trim(strip_tags($postData['contenu'])) === ''
) {
    echo 'Il manque des informations pour permettre l\'édition du formulaire.';
    return;
}

$id = (int)$postData['id'];
$titre = trim(strip_tags($postData['titre']));
$contenu = trim(strip_tags($postData['contenu']));

$insertcontenuStatement = $mysqlClient->prepare('UPDATE s2_articles_presse SET titre = :titre, contenu = :contenu WHERE id = :id');
$insertcontenuStatement->execute([
    'titre' => $titre,
    'contenu' => $contenu,
    'id' => $id,
]);

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>article modifié</title>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
        rel="stylesheet">
</head>

<body class="d-flex flex-column min-vh-100">
    <div class="container">

        <h1>article modifié avec succès !</h1>

        <div class="card">

            <div class="card-body">
                <h5 class="card-title"><?php echo ($titre); ?></h5>
                <p class="card-text"><?php echo $contenu; ?></p>
            </div>
        </div>
    </div>
</body>

</html>