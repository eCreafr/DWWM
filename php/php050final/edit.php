<?php

include('db.php');

$getData = $_GET;

if (!isset($getData['id']) || !is_numeric($getData['id'])) {
    echo ('Il faut un identifiant de news pour la modifier ! ');
    return;
}

$retrieveArticleStatement = $mysqlClient->prepare('SELECT * FROM s2_articles_presse a LEFT JOIN s2_resultats_sportifs r ON a.match_id = r.id   WHERE a.id = :id');
$retrieveArticleStatement->execute([
    'id' => (int)$getData['id'],
]);
$article = $retrieveArticleStatement->fetch(PDO::FETCH_ASSOC);

// si l'article du match n'est pas trouvée, renvoyer un message d'erreur
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edition d'article / match</title>
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="d-flex flex-column min-vh-100">
    <div class="container">

        <h1>Mettre à jour <?php echo ($article['titre']); ?></h1>
        <form action="editpost.php" method="POST">

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

            <?php

            if ($article['match_id'] > 0) {
                echo " <!-- Nouvelle case à cocher pour le match -->
            <div class=\"mb-3 form-check\">
                <input type=\"checkbox\" class=\"form-check-input\" id=\"modifierMatch\" name=\"modifierMatch\">
                <label class=\"form-check-label\" for=\"modifierMatch\">Voulez-vous modifier aussi les résultats du match ?</label>
            </div>";
            }
            ?>

            <!-- Section pour les détails du match (cachée par défaut) -->
            <div id="detailsMatch" style="display: none;">
                <div class="mb-3">
                    <label for="equipe1" class="form-label">Équipe 1</label>
                    <input type="text" class="form-control" id="equipe1" name="equipe1" value="<?php echo ($article['equipe1']); ?>">
                </div>
                <div class="mb-3">
                    <label for="equipe2" class="form-label">Équipe 2</label>
                    <input type="text" class="form-control" id="equipe2" name="equipe2" value="<?php echo ($article['equipe2']); ?>">
                </div>
                <div class="mb-3">
                    <label for="score" class="form-label">Score</label>
                    <input type="text" class="form-control" id="score" name="score" value="<?php echo ($article['score']); ?>">
                </div>
                <div class="mb-3">
                    <label for="lieu" class="form-label">Lieu</label>
                    <input type="text" class="form-control" id="lieu" name="lieu" value="<?php echo ($article['lieu']); ?>">
                </div>
                <div class="mb-3">
                    <label for="resume" class="form-label">Commentaire sur le match</label>
                    <textarea class="form-control" id="resume" name="resume">     <?php echo $article['resume']; ?></textarea>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Envoyer</button>
            <a class="btn btn-primary" role="button" href="./">RETOUR</a>
        </form>
        <br />
    </div>

    <script>
        document.getElementById('modifierMatch').addEventListener('change', function() {
            const detailsMatch = document.getElementById('detailsMatch');
            detailsMatch.style.display = this.checked ? 'block' : 'none';
        });
    </script>

</body>

</html>