<?php

include('php050-connect.php');

$getData = $_GET;

if (!isset($getData['id']) || !is_numeric($getData['id'])) {
    echo ('Il faut un identifiant pour supprimer un article. par exemple :  http://lateste.fr/git/php/php050-CRUD/php050-D.php?id=9 ');
    return;
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprimer un article</title>
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="d-flex flex-column min-vh-100">
    <div class="container">

        <h1>Supprimer l'article, c'est sur ???</h1>
        <form action="php050-D-post.php" method="POST">
            <div class="mb-3 visually-hidden">
                <label for="id" class="form-label">voulez vous supprimer l'article <?php echo $getData['id']; ?> ?</label>
                <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $getData['id']; ?>">
            </div>

            <button type="submit" class="btn btn-danger">Oui !</button> <a class="btn btn-primary" role="button" href="php050-R.php">non, RETOUR</a>
        </form>
        <br />
    </div>

</body>

</html>