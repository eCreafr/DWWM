<!-- ============================================================
     PAGE D'AJOUT D'UN ARTICLE - ADD.PHP
     ============================================================
     Cette page affiche un formulaire permettant d'ajouter
     un nouvel article sportif avec image et résultats de match.
     Les données seront envoyées à addpost.php pour traitement.
     ============================================================ -->

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

        <!-- Formulaire d'ajout d'article -->
        <!-- action="addpost.php" : les données seront envoyées au fichier addpost.php -->
        <!-- method="POST" : envoi sécurisé des données (non visible dans l'URL) -->
        <!-- enctype="multipart/form-data" : OBLIGATOIRE pour envoyer des fichiers (images) -->
        <form action="addpost.php" method="POST" enctype="multipart/form-data">

            <!-- Champ : Auteur de l'article -->
            <div class="mb-3">
                <label for="auteur" class="form-label">Auteur de l'article</label>
                <!-- name="auteur" : le nom qui sera utilisé dans $_POST['auteur'] -->
                <input type="text" class="form-control" id="auteur" name="auteur">
            </div>

            <!-- Champ : Titre de l'article -->
            <div class="mb-3">
                <label for="titre" class="form-label">Titre de l'article</label>
                <input type="text" class="form-control" id="titre" name="titre" aria-describedby="titre-help">
                <div id="titre-help" class="form-text">Choisissez un titre percutant !</div>
            </div>

            <!-- Champ : Contenu de l'article -->
            <div class="mb-3">
                <label for="contenu" class="form-label">Contenu de l'article</label>
                <!-- textarea pour un texte long sur plusieurs lignes -->
                <textarea class="form-control" placeholder="Seulement du contenu vous appartenant ou libre de droits." id="contenu" name="contenu"></textarea>
            </div>

            <!-- Champ : Upload d'image -->
            <div class="mb-3">
                <label for="image">Importer votre image :</label>
                <!-- type="file" : permet de sélectionner un fichier -->
                <!-- accept : limite aux fichiers JPG/JPEG uniquement -->
                <!-- L'image sera renommée avec l'id de l'article (ex: 5.jpg) dans addpost.php -->
                <input class="form-control" type="file" name="image" id="image" accept=".jpg, .jpeg, image/jpeg">
            </div>

            <!-- ============================================ -->
            <!-- SECTION OPTIONNELLE : RÉSULTATS DU MATCH -->
            <!-- ============================================ -->

            <!-- Case à cocher pour afficher/masquer les champs du match -->
            <div class="mb-3 form-check">
                <!-- Quand on coche cette case, les champs du match s'affichent (grâce au JavaScript en bas) -->
                <input type="checkbox" class="form-check-input" id="ajouterMatch" name="ajouterMatch">
                <label class="form-check-label" for="ajouterMatch">Voulez-vous saisir les résultats d'un match ?</label>
            </div>

            <!-- Section des détails du match (cachée par défaut avec display: none) -->
            <!-- Elle devient visible quand on coche la case ci-dessus -->
            <div id="detailsMatch" style="display: none;">

                <!-- Champ : Équipe 1 -->
                <div class="mb-3">
                    <label for="equipe1" class="form-label">Équipe 1</label>
                    <input type="text" class="form-control" id="equipe1" name="equipe1">
                </div>

                <!-- Champ : Équipe 2 -->
                <div class="mb-3">
                    <label for="equipe2" class="form-label">Équipe 2</label>
                    <input type="text" class="form-control" id="equipe2" name="equipe2">
                </div>

                <!-- Champ : Score du match -->
                <div class="mb-3">
                    <label for="score" class="form-label">Score</label>
                    <input type="text" class="form-control" id="score" name="score">
                </div>

                <!-- Champ : Lieu du match -->
                <div class="mb-3">
                    <label for="lieu" class="form-label">Lieu</label>
                    <input type="text" class="form-control" id="lieu" name="lieu">
                </div>

                <!-- Champ : Commentaire sur le match (facultatif) -->
                <div class="mb-3">
                    <label for="resume" class="form-label">Commentaire sur le match (facultatif)</label>
                    <textarea class="form-control" id="resume" name="resume"></textarea>
                </div>
            </div>

            <!-- Boutons de soumission et retour -->
            <button type="submit" class="btn btn-primary">Envoyer</button>
            <a class="btn btn-primary" role="button" href="./">RETOUR</a>
        </form>


        <!-- ============================================ -->
        <!-- JAVASCRIPT : Afficher/masquer les champs du match -->
        <!-- ============================================ -->
        <script>
            // On écoute le changement d'état de la checkbox "ajouterMatch"
            document.getElementById('ajouterMatch').addEventListener('change', function() {
                // On récupère la div contenant les détails du match
                const detailsMatch = document.getElementById('detailsMatch');

                // Si la case est cochée (this.checked = true), on affiche (block), sinon on cache (none)
                // C'est un opérateur ternaire : condition ? si_vrai : si_faux
                detailsMatch.style.display = this.checked ? 'block' : 'none';
            });
        </script>
    </div>

</body>

</html>