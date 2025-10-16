<?php
/**
 * ============================================
 * UPDATE (CRUD) - FORMULAIRE DE MODIFICATION D'ARTICLE
 * ============================================
 *
 * Ce fichier affiche un formulaire pré-rempli avec les données d'un article existant
 * C'est la partie "UPDATE" du CRUD (Create, Read, Update, Delete)
 *
 * Fonctionnement :
 * 1. On récupère l'ID de l'article à modifier depuis l'URL (?id=5)
 * 2. On charge les données de cet article depuis la BDD
 * 3. On pré-remplit le formulaire avec ces données
 * 4. L'utilisateur modifie ce qu'il veut et envoie vers php050-U-post.php
 */

// ============================================
// 1. CONNEXION À LA BASE DE DONNÉES
// ============================================
include('php050-connect.php');

// ============================================
// 2. RÉCUPÉRATION ET VALIDATION DE L'ID
// ============================================
/**
 * RÈGLE DE SÉCURITÉ IMPORTANTE :
 * On ne fait JAMAIS confiance aux données utilisateur (GET, POST, cookies, etc.)
 * Ces données doivent TOUJOURS être testées et validées avant utilisation
 */
$getData = $_GET; // $_GET contient les paramètres de l'URL (ex: ?id=5)

// Vérification que l'ID est présent et valide
if (!isset($getData['id']) || !is_numeric($getData['id'])) {
    // Si pas d'ID ou ID invalide, on arrête tout et on affiche un message d'erreur
    echo ('Il faut un identifiant de news pour la modifier. Exemple : http://lateste.fr/git/php/php050-U.php?id=5');
    return; // Arrête l'exécution du script
}

// ============================================
// 3. RÉCUPÉRATION DE L'ARTICLE EN BASE
// ============================================
// On prépare une requête pour récupérer le titre et le contenu de l'article
// SELECT : on ne récupère QUE les champs qu'on va modifier
// WHERE id = :id : on cible un article précis grâce à son ID unique
$retrieveArticleStatement = $mysqlClient->prepare('SELECT titre, contenu FROM s2_articles_presse WHERE id = :id');

// On exécute la requête avec l'ID (converti en int pour plus de sécurité)
$retrieveArticleStatement->execute([
    'id' => (int)$getData['id'], // (int) force la conversion en nombre entier
]);

// fetch() : récupère UNE SEULE ligne de résultat (contrairement à fetchAll())
// PDO::FETCH_ASSOC : retourne un tableau associatif (avec les noms de colonnes comme clés)
$article = $retrieveArticleStatement->fetch(PDO::FETCH_ASSOC);

// ============================================
// 4. VÉRIFICATION QUE L'ARTICLE EXISTE
// ============================================
// Si aucun article n'est trouvé, $article sera false ou null
if (!$article) {
    echo ('Article introuvable. Vérifiez l\'ID fourni.');
    return;
}

// Si on arrive ici, c'est que l'article existe et est chargé dans $article
?>

<!-- ============================================
     5. AFFICHAGE DU FORMULAIRE PRÉ-REMPLI
     ============================================ -->

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edition d'article</title>
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="d-flex flex-column min-vh-100">
    <div class="container">

        <!-- Titre dynamique avec le nom de l'article -->
        <h1>Mettre à jour <?php echo ($article['titre']); ?></h1>

        <!-- ============================================
             FORMULAIRE DE MODIFICATION
             ============================================
             - action="php050-U-post.php" : où les données seront envoyées
             - method="POST" : les données sont cachées (plus sûr que GET pour les modifications)
        -->
        <form action="php050-U-post.php" method="POST">

            <!-- CHAMP CACHÉ : ID DE L'ARTICLE -->
            <!-- Ce champ est invisible (visually-hidden) mais indispensable !
                 Il permet de savoir QUEL article modifier dans php050-U-post.php -->
            <div class="mb-3 visually-hidden">
                <label for="id" class="form-label">Identifiant de la news</label>
                <!--
                    type="hidden" : le champ n'est pas visible à l'écran
                    value="<?php echo ($getData['id']); ?>" : on garde l'ID récupéré depuis l'URL
                    C'est ESSENTIEL car sinon on ne saurait pas quel article mettre à jour !
                -->
                <input type="hidden" class="form-control" id="id" name="id" value="<?php echo ($getData['id']); ?>">
            </div>

            <!-- CHAMP : TITRE (pré-rempli) -->
            <div class="mb-3">
                <label for="titre" class="form-label">Titre </label>
                <!--
                    value="<?php echo ($article['titre']); ?>" : pré-remplit le champ avec le titre actuel
                    L'utilisateur peut le modifier s'il le souhaite
                -->
                <input type="text" class="form-control" id="titre" name="titre" aria-describedby="titre-help" value="<?php echo ($article['titre']); ?>">
                <div id="titre-help" class="form-text">Choisissez un titre percutant !</div>
            </div>

            <!-- CHAMP : CONTENU (pré-rempli) -->
            <div class="mb-3">
                <label for="contenu" class="form-label">Contenu</label>
                <!--
                    Pour un <textarea>, le contenu va ENTRE les balises (pas dans un attribut value)
                    Le contenu actuel de l'article est affiché, l'utilisateur peut le modifier
                -->
                <textarea class="form-control" placeholder="" id="contenu" name="contenu"><?php echo $article['contenu']; ?></textarea>
            </div>

            <!-- BOUTONS D'ACTION -->
            <button type="submit" class="btn btn-primary">Envoyer</button>
            <a class="btn btn-primary" role="button" href="php050-R.php">RETOUR</a>
        </form>
        <br />
    </div>


</body>

</html>

<!--
NOTES PÉDAGOGIQUES :

1. DIFFÉRENCE ENTRE CREATE ET UPDATE :
   - CREATE (php050-C.php) : formulaire vide, on crée un nouvel article
   - UPDATE (php050-U.php) : formulaire pré-rempli, on modifie un article existant

2. UTILISATION DE $_GET :
   - $_GET récupère les paramètres dans l'URL : ?id=5
   - Utilisé ici car on veut que l'URL soit partageable (ex: "Modifier l'article 5")
   - Plus pratique que POST pour ce cas d'usage

3. IMPORTANCE DU CHAMP CACHÉ (hidden) :
   - Sans l'ID, impossible de savoir quel article mettre à jour !
   - type="hidden" : le champ existe dans le formulaire mais n'est pas visible
   - Quand on soumet le formulaire, l'ID sera envoyé en POST vers php050-U-post.php

4. PRÉ-REMPLISSAGE DES CHAMPS :
   - <input value="..."> pour les champs input
   - <textarea>...</textarea> pour les zones de texte (contenu ENTRE les balises)
   - Permet à l'utilisateur de voir les valeurs actuelles et de ne modifier que ce qui est nécessaire

5. SÉCURITÉ - VALIDATION DE L'ID :
   - isset() : vérifie que la variable existe
   - is_numeric() : vérifie que c'est un nombre (empêche les injections)
   - (int) : conversion forcée en entier pour plus de sécurité

6. fetch() vs fetchAll() :
   - fetch() : récupère UNE ligne (car on cherche UN article précis)
   - fetchAll() : récupère TOUTES les lignes (utilisé dans php050-R.php pour tous les articles)

7. AMÉLIORATION POSSIBLE :
   - Utiliser htmlspecialchars() lors de l'affichage pour éviter les failles XSS
   - Ajouter un champ pour modifier l'auteur
   - Afficher un message si l'article n'existe pas (au lieu de juste return)
   - Ajouter une confirmation JavaScript avant l'envoi
-->
