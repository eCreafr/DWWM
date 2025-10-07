<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>php050 READ - Lecture des articles</title>
</head>

<body>

    <?php
    /**
     * ============================================
     * READ (CRUD) - AFFICHAGE DES ARTICLES
     * ============================================
     *
     * Ce fichier affiche la liste de tous les articles présents en base de données
     * C'est la partie "READ" du CRUD (Create, Read, Update, Delete)
     *
     * Fonctionnalités :
     * - Récupère tous les articles de la BDD avec une jointure SQL
     * - Affiche les articles avec un formatage HTML
     * - Tronque les textes trop longs pour un affichage propre
     */

    // ============================================
    // 1. CONNEXION À LA BASE DE DONNÉES
    // ============================================
    // On se connecte à la BDD en incluant le fichier de connexion
    include('php050-connect.php');
    // Si tout va bien, on peut continuer


    // ============================================
    // 2. RÉCUPÉRATION DES ARTICLES EN BASE
    // ============================================
    // On récupère tout le contenu de la table articles avec une requête SQL complexe

    $sqlQuery = '
        SELECT a.id, a.titre, a.contenu, a.date_publication AS textequejeveux, r.score, r.lieu
        FROM s2_articles_presse a
        LEFT JOIN s2_resultats_sportifs r ON a.match_id = r.id
        ORDER BY `a`.`date_publication`
        DESC;';

    /*
     * Explication de la requête SQL :
     *
     * SELECT : colonnes qu'on veut récupérer
     *   - a.id, a.titre, a.contenu : infos de l'article
     *   - a.date_publication AS textequejeveux : on renomme la colonne (alias)
     *   - r.score, r.lieu : infos du match sportif associé
     *
     * FROM s2_articles_presse a : table principale (alias "a" pour simplifier)
     *
     * LEFT JOIN s2_resultats_sportifs r : jointure avec la table des résultats sportifs
     *   - LEFT JOIN : garde tous les articles MÊME s'ils n'ont pas de match associé
     *   - ON a.match_id = r.id : condition de jointure (lien entre les tables)
     *   - Si match_id = 0 ou NULL, r.score et r.lieu seront NULL
     *
     * ORDER BY a.date_publication DESC : trie par date décroissante (les plus récents d'abord)
     */

    // Préparation et exécution de la requête
    $newsFraiches = $mysqlClient->prepare($sqlQuery);
    $newsFraiches->execute();

    // fetchAll() : récupère TOUS les résultats sous forme de tableau
    // Chaque élément du tableau $news est un article (lui-même un tableau associatif)
    $news = $newsFraiches->fetchAll();


    // ============================================
    // 3. FONCTION UTILITAIRE : TRONQUER LE TEXTE
    // ============================================
    /**
     * Tronque une chaîne de caractères trop longue pour l'affichage
     *
     * @param string $string Le texte à tronquer
     * @param int $length La longueur maximale (par défaut 20 caractères)
     * @return string Le texte tronqué avec "(...)" ou le texte complet s'il est court
     */
    function truncateString($string, $length = 20)
    {
        // strlen() : compte le nombre de caractères
        if (strlen($string) > $length) {
            // substr() : extrait une partie de la chaîne (de 0 à $length)
            return substr($string, 0, $length) . ' (...)';
        }
        // Si le texte est assez court, on le retourne tel quel
        return $string;
    }

    // ============================================
    // 4. AFFICHAGE DES ARTICLES
    // ============================================
    echo '<h1>étape 2 / on affiche une boucle de news contenues dans la BDD avec un SQL qui LEFT JOIN</h1><hr>';

    // Boucle foreach : parcourt tous les articles récupérés
    // À chaque tour, $new contient un article différent
    foreach ($news as $new) {

    ?>
        <!-- Affichage de l'ID unique de l'article (clé primaire en BDD) -->
        id unique : <?= $new['id']; ?>

        <!-- Affichage du titre tronqué à 20 caractères -->
        <h2><?= truncateString($new['titre'], 20); ?> :</h2>

        <!-- Affichage du score en rouge (peut être NULL si pas de match associé) -->
        <strong style="color:#FF0000"> <?= $new['score']; ?></strong>
        (lieu : <?= $new['lieu']; ?>)

        <!-- Affichage du contenu tronqué à 100 caractères -->
        <?= truncateString($new['contenu'], 100); ?>

        <!-- Affichage de la date (avec l'alias "textequejeveux") -->
        <p>-<?= $new['textequejeveux']; ?>
        </p><br><br><br><br>
        <hr>
    <?php
    } // Fin de la boucle foreach
    ?>


</body>

</html>

<!--
NOTES PÉDAGOGIQUES :

1. JOINTURE SQL (LEFT JOIN) :
   - Permet de combiner des données de plusieurs tables
   - LEFT JOIN garde tous les éléments de la table de gauche (articles)
   - Même si aucun match ne correspond, l'article sera affiché (avec NULL pour le score)
   - Différence avec INNER JOIN : INNER JOIN n'afficherait QUE les articles avec un match

2. ALIAS SQL (AS) :
   - "a" et "r" : alias de tables (pour écrire moins de texte)
   - "textequejeveux" : alias de colonne (renomme date_publication)
   - Utile pour rendre le code plus lisible

3. BOUCLE FOREACH :
   - Parcourt automatiquement tous les éléments d'un tableau
   - Syntaxe : foreach ($tableau as $element) { ... }
   - À chaque tour, $element prend la valeur de l'élément suivant

4. FONCTIONS PHP UTILES :
   - strlen() : longueur d'une chaîne
   - substr() : extraction d'une partie de chaîne
   - fetchAll() : récupère tous les résultats SQL d'un coup
   - prepare() + execute() : requête préparée (même si ici pas de paramètres utilisateur)

5. <?= ?> vs <?php echo ?> :
   - <?= $variable ?> est un raccourci pour <?php echo $variable ?>
   - Plus rapide à écrire pour l'affichage simple

6. AMÉLIORATION POSSIBLE :
   - Ajouter htmlspecialchars() pour sécuriser l'affichage (éviter XSS)
   - Paginer les résultats si beaucoup d'articles (LIMIT et OFFSET en SQL)
   - Ajouter des boutons Modifier/Supprimer pour chaque article
   - Utiliser un vrai template HTML/CSS (Bootstrap) pour un meilleur rendu
   - Gérer le cas où $news est vide (aucun article en BDD)
-->
