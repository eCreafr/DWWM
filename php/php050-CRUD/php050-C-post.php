<?php

include('php050-connect.php');


$postData = $_POST;

// Vérification du formulaire soumis
if (
    empty($postData['titre'])
    || empty($postData['contenu'])
    || empty($postData['auteur'])
    || trim(strip_tags($postData['titre'])) === ''
    || trim(strip_tags($postData['contenu'])) === ''
    || trim(strip_tags($postData['auteur'])) === ''
) {
    echo 'Il faut un titre + un contenu + un auteur pour soumettre le formulaire, sinon ça marche aps.';
    return;
}

$titre = trim(strip_tags($postData['titre']));
$contenu = trim(strip_tags($postData['contenu']));
$auteur = trim(strip_tags($postData['auteur']));

// Faire l'insertion en base
$insertcontenu = $mysqlClient->prepare('INSERT INTO s2_articles_presse(titre, contenu, auteur, date_publication, match_id) VALUES (:titre, :contenu, :auteur, :date_publication, :match_id)');
$insertcontenu->execute([
    'titre' => $titre,
    'contenu' => $contenu,
    'auteur' => $auteur,
    'date_publication' => date('Y-m-d'), // Génère automatiquement la date au format YYYY-MM-DD
    'match_id' => 0, // pour l'instant on met 0 mais ça pouurait lier ici l'article a un match 

]);

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ajout dans BDD</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="d-flex flex-column min-vh-100">
    <div class="container">

        <h1>Article ajouté avec succès ! </h1>

        <div class="card">

            <div class="card-body">
                <h5 class="card-title"><?php echo $titre; ?></h5>
                <p class="card-text"><b>Par <?php echo $auteur; ?></b> </p>
                <p class="card-text"> <?php echo $contenu; ?></p>
            </div>
        </div> <a class="btn btn-primary" role="button" href="php050tp2.php">RETOUR</a>
    </div>
</body>

</html>