<?php
include('db.php');

$postData = $_POST;

// Vérifications initiales si on a des infos de base avnt de contiuer
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

// Mise à jour de l'article
$id = (int)$postData['id'];
$titre = trim(strip_tags($postData['titre']));
$contenu = trim(strip_tags($postData['contenu']));

$insertcontenuStatement = $mysqlClient->prepare('UPDATE s2_articles_presse SET titre = :titre, contenu = :contenu WHERE id = :id');
$insertcontenuStatement->execute([
    'titre' => $titre,
    'contenu' => $contenu,
    'id' => $id,
]);

// Modification du match SEULEMENT si demandé
if (isset($postData['modifierMatch']) && $postData['modifierMatch'] === 'on') {
    // Récupération de l'id du match associé
    $sqlQuery = '
        SELECT match_id
        FROM s2_articles_presse 
        WHERE id = :id';

    $matchStatement = $mysqlClient->prepare($sqlQuery);
    $matchStatement->execute([
        'id' => $id
    ]);
    $match = $matchStatement->fetch();

    if ($match && $match['match_id']) {
        // Vérification des champs du match obligatoire s'ils sont la sauf resumé commentaire facultatif
        if (
            empty($postData['equipe1'])
            || empty($postData['equipe2'])
            || empty($postData['score'])
            || empty($postData['lieu'])
        ) {
            echo 'Tous les champs du match doivent être remplis.';
            return;
        }

        // Nettoyer les données du match
        $equipe1 = trim(strip_tags($postData['equipe1']));
        $equipe2 = trim(strip_tags($postData['equipe2']));
        $score = trim(strip_tags($postData['score']));
        $lieu = trim(strip_tags($postData['lieu']));
        $resume = trim(strip_tags($postData['resume'])); // facultatif

        // Mise à jour du match avec l'id récupéré
        $insertMatch = $mysqlClient->prepare('
            UPDATE s2_resultats_sportifs 
            SET equipe1 = :equipe1, 
                equipe2 = :equipe2, 
                score = :score, 
                lieu = :lieu, 
                resume = :resume 
            WHERE id = :id_match
        ');

        $insertMatch->execute([
            'equipe1' => $equipe1,
            'equipe2' => $equipe2,
            'score' => $score,
            'lieu' => $lieu,
            'resume' => $resume,
            'id_match' => (int)$match['match_id']
        ]);
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>article <?php echo ($id); ?> modifié</title>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
        rel="stylesheet">
</head>

<body class="d-flex flex-column min-vh-100">
    <div class="container">

        <h1>article <?php echo ($id); ?> modifié avec succès !</h1>

        <div class="card">

            <div class="card-body">
                <h5 class="card-title"><?php echo ($titre); ?></h5>
                <p class="card-text"><?php echo $contenu; ?></p>
            </div>
        </div>
    </div>
    <a class="btn btn-primary" role="button" href="./">RETOUR</a>
</body>

</html>