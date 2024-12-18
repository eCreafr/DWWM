<?php

$getData = $_GET;

if (!isset($getData['id']) || !is_numeric($getData['id'])) {
    echo ('Il faut un identifiant pour supprimer un article ! ');
    return;
}
?>


<div class="d-flex flex-column min-vh-100">
    <div class="container">

        <h1>Supprimer l'article, c'est sur ???</h1>

        <form action="deletepost.html" method="POST">
            <div class="mb-3 visually-hidden">
                <label for="id" class="form-label">Identifiant de l'article</label>
                <input type="hidden" class="form-control" id="id" name="id" value="<?= $getData['id']; ?>">
            </div>


            <!-- Nouvelle case à cocher pour le match -->
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="supprimerMatch" name="supprimerMatch">
                <label class="form-check-label" for="supprimerMatch">Voulez-vous aussi supprimer les résultats du match associé ?</label>
            </div>

            <button type="submit" class="btn btn-danger">La suppression est définitive</button>
            <a class="btn btn-primary" role="button" href="home.html">RETOUR</a>

        </form>

    </div>




</div>