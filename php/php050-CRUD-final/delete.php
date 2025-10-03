<?php
// ============================================================
// PAGE DE CONFIRMATION DE SUPPRESSION - DELETE.PHP
// ============================================================
// Cette page affiche un formulaire de confirmation avant
// de supprimer définitivement un article et son match associé.
// ============================================================

// Inclusion de la connexion à la base de données
include('db.php');

// Récupération des données depuis l'URL (paramètre GET)
$getData = $_GET;

// ============================================================
// VALIDATION DE L'ID DE L'ARTICLE
// ============================================================

// Vérification que l'ID existe dans l'URL et qu'il est numérique
if (!isset($getData['id']) || !is_numeric($getData['id'])) {
    echo ('Il faut un identifiant pour supprimer un article ! ');
    return; // Arrête l'exécution du script
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

        <h1>Supprimer l'article, c'est sûr ???</h1>

        <!-- ============================================ -->
        <!-- FORMULAIRE DE CONFIRMATION DE SUPPRESSION -->
        <!-- ============================================ -->
        <!-- Les données seront envoyées à deletepost.php pour effectuer la suppression -->
        <form action="deletepost.php" method="POST">

            <!-- Champ caché contenant l'ID de l'article à supprimer -->
            <!-- visually-hidden : la div est masquée visuellement mais le champ reste actif -->
            <div class="mb-3 visually-hidden">
                <label for="id" class="form-label">Identifiant de la recette</label>
                <!-- type="hidden" : le champ n'est pas visible mais envoie l'ID en POST -->
                <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $getData['id']; ?>">
            </div>


            <!-- ============================================ -->
            <!-- OPTION : SUPPRIMER AUSSI LE MATCH ASSOCIÉ -->
            <!-- ============================================ -->

            <!-- Case à cocher pour supprimer aussi le match -->
            <!-- Si cochée, deletepost.php supprimera aussi le match -->
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="supprimerMatch" name="supprimerMatch">
                <label class="form-check-label" for="supprimerMatch">Voulez-vous aussi supprimer les résultats du match associé ?</label>
            </div>

            <!-- Bouton de confirmation (rouge = danger) -->
            <button type="submit" class="btn btn-danger">La suppression est définitive</button>

            <!-- Bouton pour annuler et revenir à l'accueil -->
            <a class="btn btn-primary" role="button" href="./">RETOUR</a>

        </form>

    </div>




</body>

</html>
