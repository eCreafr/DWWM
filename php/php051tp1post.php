<?php
include('php050connect.php');

$postData = $_POST;

// Vérification du formulaire soumis pour l'article (toujours requis)
if (
    empty($postData['titre'])
    || empty($postData['contenu'])
    || empty($postData['auteur'])
    || trim(strip_tags($postData['titre'])) === ''
    || trim(strip_tags($postData['contenu'])) === ''
    || trim(strip_tags($postData['auteur'])) === ''
) {
    echo 'Il faut un titre + un contenu + un auteur pour soumettre le formulaire.';
    return;
}

// Nettoyer les données de l'article
$titre = trim(strip_tags($postData['titre']));
$contenu = trim(strip_tags($postData['contenu']));
$auteur = trim(strip_tags($postData['auteur']));

// Initialiser match_id à 0 par défaut
$match_id = 0;

// Vérifier si un match doit être ajouté
if (isset($postData['ajouterMatch']) && $postData['ajouterMatch'] === 'on') {
    // Vérifier que tous les champs du match sont remplis
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

    // 1ere étape : on doit insérer d'abord le match pour obtenir son ID
    $insertMatch = $mysqlClient->prepare('
        INSERT INTO s2_resultats_sportifs(equipe1, equipe2, score, lieu, resume, date_match)
        VALUES (:equipe1, :equipe2, :score, :lieu, :resume, :date_match)
    ');

    $insertMatch->execute([
        'equipe1' => $equipe1,
        'equipe2' => $equipe2,
        'score' => $score,
        'lieu' => $lieu,
        'date_match' => date('Y-m-d'),
        'resume' => $resume,
    ]);

    // Récupérer l'ID du match qui vient d'être inséré
    $match_id = $mysqlClient->lastInsertId();
}

// 2eme étape, on peut insérer l'article avec la référence au match (si existant car facultatif)
$insertArticle = $mysqlClient->prepare('
    INSERT INTO s2_articles_presse(titre, contenu, auteur, date_publication, match_id)
    VALUES (:titre, :contenu, :auteur, :date_publication, :match_id)
');

$insertArticle->execute([
    'titre' => $titre,
    'contenu' => $contenu,
    'auteur' => $auteur,
    'date_publication' => date('Y-m-d'),
    'match_id' => $match_id,
]);

// Préparer les données du match pour l'affichage
$matchInfo = '';


if ($match_id > 0) {
    $matchInfo = "<div class='card mt-3'>
        <div class='card-body'>
            <h5 class='card-title'>Informations du match</h5>
            <p>{$equipe1} vs {$equipe2}</p>
            <p>Score : {$score}</p>
            <p>Lieu : {$lieu}</p>
            " . ($resume ? "<p>Commentaire : {$resume}</p>" : "") . "
        </div>
    </div>";
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Article ajouté</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="d-flex flex-column min-vh-100">
    <div class="container">
        <h1>Article ajouté avec succès !</h1>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><?php echo $titre; ?></h5>
                <p class="card-text"><b>Par <?php echo $auteur; ?></b></p>
                <p class="card-text"><?php echo $contenu; ?></p>
            </div>
        </div>
        <?php echo $matchInfo; ?>
    </div>

    <a class="btn btn-primary" role="button" href="php050tp2.php">RETOUR</a>
</body>

</html>