<?php

include('php050-connect.php');

$getData = $_GET;

if (!isset($getData['id']) || !is_numeric($getData['id'])) {
    echo ('Il faut un identifiant pour supprimer un article. http://lateste.fr/git/php/php052.php?id=9 ');
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
    <link href="../css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="d-flex flex-column min-vh-100">
    <div class="container">

        <h1>Supprimer l'article, c'est sur ???</h1>
        <form action="php052post.php" method="POST">
            <div class="mb-3 visually-hidden">
                <label for="id" class="form-label">Identifiant de la recette</label>
                <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $getData['id']; ?>">
            </div>

            <button type="submit" class="btn btn-danger">La suppression est définitive</button> <a class="btn btn-primary" role="button" href="php050tp2.php">RETOUR</a>
        </form>
        <br />
    </div>

</body>

</html>