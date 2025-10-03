<!-- dans les fichiers add.php et addpost.php je souhaite ajouter un champs pour importer une image. l'image importé sera renommé avec l'id récupéré de l'article ajouté a la base de donnée. l'image sera stockée dans img/

par exemple si l'auto incrementation donne a l'article l'id primaire 5 l'image importée sera alors img/5.jpg -->


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
        <form action="addpost.php" method="POST" enctype="multipart/form-data">
            <!-- enctype="multipart/form-data" est indispensable pour charger l'image jpg -->
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
                <label for="contenu" class="form-label">Contenu de l'article</label>
                <textarea class="form-control" placeholder="Seulement du contenu vous appartenant ou libre de droits." id="contenu" name="contenu"></textarea>
            </div>
            <div class="mb-3">
                <label for="image">Importer votre image :</label>
                <input class="form-control" type="file" name="image" id="image" accept=".jpg, .jpeg, image/jpeg">
            </div>

            <!-- Nouvelle case à cocher pour le match -->
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="ajouterMatch" name="ajouterMatch">
                <label class="form-check-label" for="ajouterMatch">Voulez-vous saisir les résultats d'un match ?</label>
            </div>

            <!-- Section pour les détails du match (cachée par défaut) -->
            <div id="detailsMatch" style="display: none;">
                <div class="mb-3">
                    <label for="equipe1" class="form-label">Équipe 1</label>
                    <input type="text" class="form-control" id="equipe1" name="equipe1">
                </div>
                <div class="mb-3">
                    <label for="equipe2" class="form-label">Équipe 2</label>
                    <input type="text" class="form-control" id="equipe2" name="equipe2">
                </div>
                <div class="mb-3">
                    <label for="score" class="form-label">Score</label>
                    <input type="text" class="form-control" id="score" name="score">
                </div>
                <div class="mb-3">
                    <label for="lieu" class="form-label">Lieu</label>
                    <input type="text" class="form-control" id="lieu" name="lieu">
                </div>
                <div class="mb-3">
                    <label for="resume" class="form-label">Commentaire sur le match (facultatif)</label>
                    <textarea class="form-control" id="resume" name="resume"></textarea>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Envoyer</button> <a class="btn btn-primary" role="button" href="./">RETOUR</a>
        </form>



        <script>
            document.getElementById('ajouterMatch').addEventListener('change', function() {
                const detailsMatch = document.getElementById('detailsMatch');
                detailsMatch.style.display = this.checked ? 'block' : 'none';
            });
        </script>
    </div>

</body>

</html>