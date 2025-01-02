<?php

include('db.php');

$getData = $_GET;

if (!isset($getData['id']) || !is_numeric($getData['id'])) {
    echo ('Il faut un identifiant pour supprimer un article ! ');
    return;
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprimer l'article <?php echo $getData['id']; ?></title>
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="d-flex flex-column min-vh-100">
    <div class="container">

        <h1>Supprimer l'article, c'est sur ???</h1>

        <form action="deletepost.php" method="POST">
            <div class="mb-3 visually-hidden">
                <label for="id" class="form-label">Identifiant de la recette</label>
                <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $getData['id']; ?>">
            </div>


            <!-- Nouvelle case à cocher pour le match -->
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="supprimerMatch" name="supprimerMatch">
                <label class="form-check-label" for="supprimerMatch">Voulez-vous aussi supprimer les résultats du match associé ?</label>
            </div>

            <button type="submit" class="btn btn-danger">La suppression est définitive</button>
            <a class="btn btn-primary" role="button" href="./">RETOUR</a>

        </form>

    </div>




</body>

</html>