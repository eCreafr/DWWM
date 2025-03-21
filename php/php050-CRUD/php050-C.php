<?php
include('php050-connect.php');
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajout d'article</title>
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="d-flex flex-column min-vh-100">
    <div class="container">


        <h1>Ajouter un article</h1>
        <form action="php050-C-post.php" method="POST">
            <div class="mb-3">
                <label for="auteur" class="form-label">Auteur de l'article</label>
                <input type="text" class="form-control" id="auteur" name="auteur">

            </div>
            <div class="mb-3">
                <label for="titre" class="form-label">Titre de l'article</label>
                <input type="text" class="form-control" id="titre" name="titre" aria-describedby="titre-help">
                <div id="titre-help" class="form-text">Choisissez un titre percutant !</div>
            </div>
            <div class="mb-3">
                <label for="contenu" class="form-label">contenu de l'article</label>
                <textarea class="form-control" placeholder="Seulement du contenu vous appartenant ou libre de droits." id="contenu" name="contenu"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Envoyer</button> <br> <a class="btn btn-primary" role="button" href="php050-R.php">ou RETOUR</a>

        </form>
    </div>

</body>

</html>