<?php
/**
 * ============================================
 * DELETE (CRUD) - PAGE DE CONFIRMATION DE SUPPRESSION
 * ============================================
 *
 * Ce fichier affiche une page de confirmation avant de supprimer un article
 * C'est la partie "DELETE" du CRUD (Create, Read, Update, Delete)
 *
 * Fonctionnement :
 * 1. On récupère l'ID de l'article à supprimer depuis l'URL (?id=9)
 * 2. On affiche une page de confirmation (sécurité : éviter les suppressions accidentelles)
 * 3. Si l'utilisateur confirme, l'ID est envoyé vers php050-D-post.php qui effectue la suppression
 *
 * IMPORTANT : La suppression est IRRÉVERSIBLE ! D'où l'importance de cette page de confirmation.
 */

// ============================================
// 1. CONNEXION À LA BASE DE DONNÉES
// ============================================
include('php050-connect.php');

// ============================================
// 2. RÉCUPÉRATION ET VALIDATION DE L'ID
// ============================================
$getData = $_GET; // $_GET contient les paramètres de l'URL (ex: ?id=9)

// Vérification que l'ID est présent et valide
if (!isset($getData['id']) || !is_numeric($getData['id'])) {
    // Si pas d'ID ou ID invalide, on arrête tout et on affiche un message d'erreur
    echo ('Il faut un identifiant pour supprimer un article. Exemple : http://lateste.fr/git/php/php050-CRUD/php050-D.php?id=9');
    return; // Arrête l'exécution du script
}

// Si on arrive ici, c'est que l'ID est valide
?>

<!-- ============================================
     3. AFFICHAGE DE LA PAGE DE CONFIRMATION
     ============================================ -->

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

        <!-- Message de confirmation avec un ton prudent -->
        <h1>Supprimer l'article, c'est sûr ???</h1>

        <!-- ============================================
             FORMULAIRE DE CONFIRMATION
             ============================================
             - action="php050-D-post.php" : où l'ID sera envoyé si l'utilisateur confirme
             - method="POST" : les données sont cachées (plus sûr que GET pour les suppressions)
        -->
        <form action="php050-D-post.php" method="POST">

            <!-- CHAMP CACHÉ : ID DE L'ARTICLE À SUPPRIMER -->
            <div class="mb-3 visually-hidden">
                <!-- Label informatif (même s'il est caché) -->
                <label for="id" class="form-label">Voulez-vous supprimer l'article <?php echo $getData['id']; ?> ?</label>

                <!--
                    type="hidden" : le champ n'est pas visible mais sera envoyé avec le formulaire
                    value="<?php echo $getData['id']; ?>" : on passe l'ID à supprimer
                -->
                <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $getData['id']; ?>">
            </div>

            <!-- BOUTONS D'ACTION -->
            <!-- Bouton DANGER (rouge) pour confirmer la suppression -->
            <button type="submit" class="btn btn-danger">Oui !</button>

            <!-- Lien pour annuler et retourner à la liste (sans supprimer) -->
            <a class="btn btn-primary" role="button" href="php050-R.php">Non, RETOUR</a>
        </form>
        <br />
    </div>

</body>

</html>

<!--
NOTES PÉDAGOGIQUES :

1. POURQUOI UNE PAGE DE CONFIRMATION ?
   - La suppression est DÉFINITIVE : on ne peut pas récupérer les données après
   - Évite les suppressions accidentelles (clic par erreur sur un bouton "Supprimer")
   - Bonne pratique UX (expérience utilisateur) : donner une chance de se rétracter
   - Sécurité : empêche les suppressions automatiques via un simple lien

2. PROCESSUS DE SUPPRESSION EN 2 ÉTAPES :
   Étape 1 (php050-D.php) : Affiche "Êtes-vous sûr ?"
   Étape 2 (php050-D-post.php) : Exécute réellement la suppression

3. UTILISATION DE $_GET :
   - L'ID est passé dans l'URL : php050-D.php?id=9
   - Pratique pour créer des liens "Supprimer" dans la liste des articles
   - Exemple : <a href="php050-D.php?id=5">Supprimer l'article 5</a>

4. CHAMP HIDDEN (caché) :
   - type="hidden" : le champ existe dans le formulaire mais n'est pas visible
   - Permet de transmettre l'ID de GET (URL) vers POST (formulaire)
   - Sans ce champ, php050-D-post.php ne saurait pas quel article supprimer !

5. BOUTON DANGER (Bootstrap) :
   - class="btn btn-danger" : affiche le bouton en rouge
   - Signale visuellement que l'action est dangereuse/irréversible
   - Conventions UI : rouge = suppression, vert = validation, bleu = action normale

6. VALIDATION DE L'ID :
   - isset() : vérifie que la variable existe
   - is_numeric() : vérifie que c'est un nombre (sécurité)
   - Empêche les tentatives de suppression avec des IDs invalides

7. AMÉLIORATION POSSIBLE :
   - Afficher les détails de l'article à supprimer (titre, auteur) pour être sûr
   - Ajouter une confirmation JavaScript en plus (alert ou modal)
   - Implémenter une "corbeille" au lieu de supprimer définitivement
   - Logger les suppressions (qui, quand, quoi) pour l'audit
   - Vérifier que l'article existe avant d'afficher la page de confirmation
   - Ajouter un système de permissions (qui a le droit de supprimer ?)

8. SÉCURITÉ CSRF :
   En production, il faudrait ajouter un token CSRF pour empêcher les attaques par formulaire externe
-->
