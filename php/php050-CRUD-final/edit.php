<?php
// ============================================================
// PAGE DE MODIFICATION D'UN ARTICLE - EDIT.PHP
// ============================================================
// Cette page affiche un formulaire pré-rempli avec les données
// d'un article existant pour permettre sa modification.
// ============================================================

// Inclusion de la connexion à la base de données
include('db.php');

// Récupération des données depuis l'URL (paramètre GET)
// $_GET est un tableau contenant les paramètres de l'URL
$getData = $_GET;

// ============================================================
// VALIDATION DE L'ID DE L'ARTICLE
// ============================================================

// Vérification que l'ID existe dans l'URL et qu'il est numérique
if (!isset($getData['id']) || !is_numeric($getData['id'])) {
    echo ('Il faut un identifiant de news pour la modifier ! ');
    return; // Arrête l'exécution du script
}

// ============================================================
// RÉCUPÉRATION DE L'ARTICLE ET DES INFOS DU MATCH
// ============================================================

// Requête SQL avec LEFT JOIN pour récupérer l'article + les données du match associé
// On utilise LEFT JOIN pour récupérer l'article même s'il n'a pas de match
$retrieveArticleStatement = $mysqlClient->prepare('SELECT * FROM s2_articles_presse a LEFT JOIN s2_resultats_sportifs r ON a.match_id = r.id   WHERE a.id = :id');

// Exécution de la requête avec l'ID de l'article
// (int) force la conversion en nombre entier pour plus de sécurité
$retrieveArticleStatement->execute([
    'id' => (int)$getData['id'],
]);

// Récupération des données de l'article
// PDO::FETCH_ASSOC : retourne un tableau associatif (avec les noms des colonnes comme clés)
$article = $retrieveArticleStatement->fetch(PDO::FETCH_ASSOC);

// Si l'article n'est pas trouvé, $article sera vide (false)
// Dans un vrai projet, on devrait gérer cette erreur !
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

        <!-- Affichage du titre de l'article à modifier -->
        <h1>Mettre à jour <?php echo ($article['titre']); ?></h1>

        <!-- Formulaire de modification -->
        <!-- Les données seront envoyées à editpost.php en méthode POST -->
        <form action="editpost.php" method="POST">

            <!-- ============================================ -->
            <!-- CHAMP CACHÉ : ID DE L'ARTICLE -->
            <!-- ============================================ -->
            <!-- Ce champ est invisible (visually-hidden) mais nécessaire -->
            <!-- pour savoir quel article modifier dans editpost.php -->
            <div class="mb-3 visually-hidden">
                <label for="id" class="form-label">Identifiant de la news</label>
                <!-- type="hidden" : le champ n'est pas visible à l'écran -->
                <!-- value contient l'ID récupéré depuis l'URL -->
                <input type="hidden" class="form-control" id="id" name="id" value="<?php echo ($getData['id']); ?>">
            </div>

            <!-- ============================================ -->
            <!-- CHAMP : TITRE DE L'ARTICLE -->
            <!-- ============================================ -->
            <div class="mb-3">
                <label for="titre" class="form-label">Titre </label>
                <!-- Le champ est pré-rempli avec la valeur actuelle du titre -->
                <input type="text" class="form-control" id="titre" name="titre" aria-describedby="titre-help" value="<?php echo ($article['titre']); ?>">
                <div id="titre-help" class="form-text">Choisissez un titre percutant !</div>
            </div>

            <!-- ============================================ -->
            <!-- CHAMP : CONTENU DE L'ARTICLE -->
            <!-- ============================================ -->
            <div class="mb-3">
                <label for="contenu" class="form-label">Contenu</label>
                <!-- textarea pour un texte long -->
                <!-- Le contenu actuel est affiché entre les balises <textarea> -->
                <textarea class="form-control" placeholder="" id="contenu" name="contenu">
                    <?php echo $article['contenu']; ?>
                </textarea>
            </div>

            <?php
            // ============================================
            // SECTION OPTIONNELLE : MODIFICATION DU MATCH
            // ============================================

            // On affiche la case à cocher seulement si l'article a un match associé
            // match_id > 0 signifie qu'il y a un match lié à cet article
            if ($article['match_id'] > 0) {
                // Affichage de la case à cocher en échappant les guillemets avec \"
                echo " <!-- Nouvelle case à cocher pour le match -->
            <div class=\"mb-3 form-check\">
                <input type=\"checkbox\" class=\"form-check-input\" id=\"modifierMatch\" name=\"modifierMatch\">
                <label class=\"form-check-label\" for=\"modifierMatch\">Voulez-vous modifier aussi les résultats du match ?</label>
            </div>";
            }
            ?>

            <!-- Section des détails du match (cachée par défaut) -->
            <!-- Elle s'affichera quand on coche la case ci-dessus (via JavaScript) -->
            <div id="detailsMatch" style="display: none;">

                <!-- Champ : Équipe 1 (pré-rempli avec les données actuelles) -->
                <div class="mb-3">
                    <label for="equipe1" class="form-label">Équipe 1</label>
                    <input type="text" class="form-control" id="equipe1" name="equipe1" value="<?php echo ($article['equipe1']); ?>">
                </div>

                <!-- Champ : Équipe 2 -->
                <div class="mb-3">
                    <label for="equipe2" class="form-label">Équipe 2</label>
                    <input type="text" class="form-control" id="equipe2" name="equipe2" value="<?php echo ($article['equipe2']); ?>">
                </div>

                <!-- Champ : Score -->
                <div class="mb-3">
                    <label for="score" class="form-label">Score</label>
                    <input type="text" class="form-control" id="score" name="score" value="<?php echo ($article['score']); ?>">
                </div>

                <!-- Champ : Lieu -->
                <div class="mb-3">
                    <label for="lieu" class="form-label">Lieu</label>
                    <input type="text" class="form-control" id="lieu" name="lieu" value="<?php echo ($article['lieu']); ?>">
                </div>

                <!-- Champ : Résumé/Commentaire du match -->
                <div class="mb-3">
                    <label for="resume" class="form-label">Commentaire sur le match</label>
                    <textarea class="form-control" id="resume" name="resume">     <?php echo $article['resume']; ?></textarea>
                </div>
            </div>

            <!-- Boutons de soumission et retour -->
            <button type="submit" class="btn btn-primary">Envoyer</button>
            <a class="btn btn-primary" role="button" href="./">RETOUR</a>
        </form>
        <br />
    </div>

    <!-- ============================================ -->
    <!-- JAVASCRIPT : Afficher/masquer les champs du match -->
    <!-- ============================================ -->
    <script>
        // On écoute le changement d'état de la checkbox "modifierMatch"
        document.getElementById('modifierMatch').addEventListener('change', function() {
            // On récupère la div contenant les détails du match
            const detailsMatch = document.getElementById('detailsMatch');

            // Si la case est cochée, on affiche les champs (block), sinon on les cache (none)
            // Opérateur ternaire : condition ? valeur_si_vrai : valeur_si_faux
            detailsMatch.style.display = this.checked ? 'block' : 'none';
        });
    </script>

</body>

</html>
