<?php
/**
 * ============================================
 * UPDATE (CRUD) - TRAITEMENT DE LA MODIFICATION
 * ============================================
 *
 * Ce fichier reçoit les données du formulaire php050-U.php et met à jour l'article en BDD
 * C'est la partie "UPDATE" du CRUD (Create, Read, Update, Delete)
 *
 * IMPORTANT : Les données utilisateur ne sont JAMAIS fiables !
 * Il faut toujours les valider et les nettoyer avant de les utiliser
 */

// ============================================
// 1. CONNEXION À LA BASE DE DONNÉES
// ============================================
include('php050-connect.php');

// ============================================
// 2. RÉCUPÉRATION DES DONNÉES DU FORMULAIRE
// ============================================
/**
 * RÈGLE DE SÉCURITÉ IMPORTANTE :
 * On ne fait JAMAIS confiance aux données utilisateur (GET, POST, cookies, etc.)
 * Ces données doivent TOUJOURS être testées et validées avant utilisation
 */
$postData = $_POST; // $_POST contient toutes les données envoyées par le formulaire

// ============================================
// 3. VALIDATION DES DONNÉES
// ============================================
// On vérifie que TOUTES les données nécessaires sont présentes et valides

if (
    !isset($postData['id'])                          // L'ID doit être présent
    || !is_numeric($postData['id'])                  // L'ID doit être un nombre
    || empty($postData['titre'])                     // Le titre ne doit pas être vide
    || empty($postData['contenu'])                   // Le contenu ne doit pas être vide
    || trim(strip_tags($postData['titre'])) === ''   // Le titre ne doit pas contenir QUE des espaces/balises
    || trim(strip_tags($postData['contenu'])) === '' // Le contenu ne doit pas contenir QUE des espaces/balises
) {
    // Si une des conditions ci-dessus est vraie, on arrête tout
    echo 'Il manque des informations pour permettre l\'édition du formulaire.';
    return; // Arrête l'exécution du script
}

// ============================================
// 4. NETTOYAGE DES DONNÉES (SÉCURITÉ)
// ============================================
// (int) : conversion forcée en entier (empêche les injections SQL)
$id = (int)$postData['id'];

// trim() : enlève les espaces au début et à la fin
// strip_tags() : enlève les balises HTML pour éviter les attaques XSS
$titre = trim(strip_tags($postData['titre']));
$contenu = trim(strip_tags($postData['contenu']));

// ============================================
// 5. MISE À JOUR EN BASE DE DONNÉES
// ============================================
// On utilise une REQUÊTE PRÉPARÉE pour éviter les injections SQL

// UPDATE : modifie les données existantes (contrairement à INSERT qui crée)
// SET : définit les nouvelles valeurs
// WHERE id = :id : CRUCIAL ! Sans WHERE, TOUS les articles seraient modifiés !
$insertcontenuStatement = $mysqlClient->prepare('UPDATE s2_articles_presse SET titre = :titre, contenu = :contenu WHERE id = :id');

// Exécution de la requête avec les nouvelles valeurs
$insertcontenuStatement->execute([
    'titre' => $titre,     // Nouveau titre
    'contenu' => $contenu, // Nouveau contenu
    'id' => $id,           // ID de l'article à modifier (condition WHERE)
]);

?>


<!-- ============================================
     6. AFFICHAGE DE LA PAGE DE CONFIRMATION
     ============================================ -->

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>article modifié</title>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
        rel="stylesheet">
</head>

<body class="d-flex flex-column min-vh-100">
    <div class="container">

        <h1>Article modifié avec succès !</h1>

        <!-- Carte Bootstrap pour afficher un aperçu de l'article modifié -->
        <div class="card">
            <div class="card-body">
                <!-- Affichage du nouveau titre -->
                <h5 class="card-title"><?php echo ($titre); ?></h5>

                <!-- Affichage du nouveau contenu -->
                <p class="card-text"><?php echo $contenu; ?></p>
            </div>
        </div>
    </div>

    <!-- Bouton pour retourner à la liste des articles -->
    <a class="btn btn-primary" role="button" href="php050-R.php">RETOUR</a>
</body>

</html>

<!--
NOTES PÉDAGOGIQUES :

1. UPDATE vs INSERT :
   - INSERT INTO : crée un NOUVEL enregistrement en BDD
   - UPDATE : MODIFIE un enregistrement existant
   - Pour UPDATE, la clause WHERE est ESSENTIELLE !

2. IMPORTANCE DE LA CLAUSE WHERE :
   Sans WHERE, la requête UPDATE modifierait TOUS les articles de la table !
   Exemple catastrophique :
   UPDATE s2_articles_presse SET titre = 'Bug'
   → TOUS les articles auraient le titre "Bug" !

   Avec WHERE :
   UPDATE s2_articles_presse SET titre = 'Bug' WHERE id = 5
   → Seul l'article n°5 est modifié ✓

3. VALIDATION DE L'ID :
   - isset() : vérifie que la variable existe
   - is_numeric() : vérifie que c'est un nombre (sécurité)
   - (int) : conversion forcée en entier (sécurité supplémentaire)

4. FLUX DE DONNÉES POUR UPDATE :
   Formulaire pré-rempli (php050-U.php?id=5)
        ↓
   Modification par l'utilisateur
        ↓
   Envoi POST vers php050-U-post.php
        ↓
   Validation + Nettoyage
        ↓
   UPDATE en BDD (WHERE id = 5)
        ↓
   Confirmation à l'utilisateur

5. DIFFÉRENCE ENTRE LES FICHIERS UPDATE :
   - php050-U.php : AFFICHE le formulaire (GET l'ID depuis l'URL)
   - php050-U-post.php : TRAITE le formulaire (POST les données modifiées)

6. SÉCURITÉ :
   - Requêtes préparées : empêchent les injections SQL
   - strip_tags() : empêche les attaques XSS
   - Validation de l'ID : empêche les modifications non autorisées

7. AMÉLIORATION POSSIBLE :
   - Vérifier que l'article existe avant de le modifier (SELECT puis UPDATE)
   - Utiliser htmlspecialchars() pour l'affichage
   - Rediriger directement vers php050-R.php au lieu d'afficher une page de confirmation
   - Ajouter un système de permissions (qui a le droit de modifier ?)
   - Logger les modifications (historique des changements)
-->
