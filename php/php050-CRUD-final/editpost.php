<?php
// ============================================================
// PAGE DE TRAITEMENT DE MODIFICATION - EDITPOST.PHP
// ============================================================
// Cette page reçoit les données du formulaire edit.php
// et met à jour l'article (et le match si demandé) dans la BDD.
// Elle inclut aussi un système de logging avancé.
// ============================================================

// Inclusion de la connexion à la base de données
include('db.php');

// ============================================================
// CLASSE JSONLOGGER : SYSTÈME DE JOURNALISATION (LOGGING)
// ============================================================
// Cette classe permet d'enregistrer toutes les actions dans un fichier JSON
// C'est très utile pour le débogage et pour tracer les modifications

class JsonLogger
{
    // Propriété privée : chemin du fichier de log
    private $logFile;

    // ============================================
    // CONSTRUCTEUR : appelé automatiquement lors de la création de l'objet
    // ============================================
    // Exemple : $logger = new JsonLogger(); ou $logger = new JsonLogger('mon_fichier.json');
    public function __construct($filename = 'articles_logs.json')
    {
        // On stocke le nom du fichier dans la propriété
        $this->logFile = $filename;

        // Si le fichier n'existe pas encore, on le crée avec un tableau vide []
        if (!file_exists($this->logFile)) {
            file_put_contents($this->logFile, '[]');
        }
    }

    // ============================================
    // MÉTHODE LOG : enregistre une action dans le fichier
    // ============================================
    // Exemple : $logger->log('update_article', ['id' => 5, 'titre' => 'Mon titre']);
    public function log($action, $data = [])
    {
        // Création d'une entrée de log avec timestamp, action, données, IP et user agent
        $logEntry = [
            'timestamp' => date('Y-m-d H:i:s'),     // Date et heure actuelles
            'action' => $action,                     // Action effectuée (ex: 'update_article')
            'data' => $data,                         // Données associées (tableau)
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',           // Adresse IP du client
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown' // Navigateur utilisé
        ];

        // Lecture du fichier de logs existant et décodage du JSON
        // ?? [] signifie : si le décodage échoue, utilise un tableau vide
        $logs = json_decode(file_get_contents($this->logFile), true) ?? [];

        // Ajout de la nouvelle entrée au tableau de logs
        $logs[] = $logEntry;

        // Ré-écriture du fichier avec tous les logs (ancien + nouveau)
        // JSON_PRETTY_PRINT rend le JSON lisible avec indentation
        file_put_contents($this->logFile, json_encode($logs, JSON_PRETTY_PRINT));
    }
}

// Création d'une instance (objet) de la classe JsonLogger
$logger = new JsonLogger();

// ============================================================
// LOGGING : DÉBUT DE LA TENTATIVE DE MODIFICATION
// ============================================================

// On enregistre le début de la modification avec toutes les données reçues
$logger->log('update_article_start', [
    'post_data' => $_POST  // Toutes les données envoyées par le formulaire
]);

// ============================================================
// RÉCUPÉRATION ET VALIDATION DES DONNÉES
// ============================================================

// Récupération des données du formulaire
$postData = $_POST;

// Vérifications initiales : on s'assure d'avoir les informations de base
if (
    !isset($postData['id'])                           // L'ID existe ?
    || !is_numeric($postData['id'])                   // L'ID est un nombre ?
    || empty($postData['titre'])                      // Le titre n'est pas vide ?
    || empty($postData['contenu'])                    // Le contenu n'est pas vide ?
    || trim(strip_tags($postData['titre'])) === ''    // Le titre contient du texte réel ?
    || trim(strip_tags($postData['contenu'])) === ''  // Le contenu contient du texte réel ?
) {

    // Si une condition n'est pas remplie, on enregistre l'erreur dans les logs
    $logger->log('update_article_error', [
        'error' => 'Validation failed',  // Type d'erreur
        'post_data' => $postData         // Données reçues (pour debug)
    ]);

    echo 'Il manque des informations pour permettre l\'édition du formulaire.';
    return; // Arrêt du script
}

// ============================================================
// NETTOYAGE DES DONNÉES DE L'ARTICLE
// ============================================================

// Conversion et nettoyage des données
$id = (int)$postData['id'];                         // Conversion forcée en entier
$titre = trim(strip_tags($postData['titre']));      // Suppression espaces + balises HTML
$contenu = trim(strip_tags($postData['contenu']));  // Suppression espaces + balises HTML

// ============================================================
// MISE À JOUR DE L'ARTICLE DANS LA BASE DE DONNÉES
// ============================================================

try {
    // ============================================
    // LOGGING : avant la modification
    // ============================================
    $logger->log('update_article_attempt', [
        'id' => $id,
        'titre' => $titre,
        'contenu_length' => strlen($contenu)  // Longueur du contenu (pour info)
    ]);

    // ============================================
    // REQUÊTE SQL : UPDATE (mise à jour)
    // ============================================
    // On met à jour uniquement le titre et le contenu de l'article
    $insertcontenuStatement = $mysqlClient->prepare('UPDATE s2_articles_presse SET titre = :titre, contenu = :contenu WHERE id = :id');

    $insertcontenuStatement->execute([
        'titre' => $titre,
        'contenu' => $contenu,
        'id' => $id,
    ]);

    // ============================================
    // LOGGING : succès de la mise à jour
    // ============================================
    $logger->log('update_article_success', [
        'id' => $id,
        'rows_affected' => $insertcontenuStatement->rowCount()  // Nombre de lignes modifiées (devrait être 1)
    ]);

    // ============================================================
    // MODIFICATION OPTIONNELLE DU MATCH
    // ============================================================

    // Si l'utilisateur a coché la case "modifierMatch"
    if (isset($postData['modifierMatch']) && $postData['modifierMatch'] === 'on') {

        $logger->log('update_match_attempt', [
            'article_id' => $id
        ]);

        // ============================================
        // RÉCUPÉRATION DE L'ID DU MATCH ASSOCIÉ
        // ============================================

        // On récupère le match_id de l'article pour savoir quel match modifier
        $sqlQuery = 'SELECT match_id FROM s2_articles_presse WHERE id = :id';
        $matchStatement = $mysqlClient->prepare($sqlQuery);
        $matchStatement->execute([
            'id' => $id
        ]);
        $match = $matchStatement->fetch();

        // Si un match existe (match_id n'est pas nul)
        if ($match && $match['match_id']) {

            // ============================================
            // VALIDATION DES CHAMPS DU MATCH
            // ============================================

            // Vérification que tous les champs du match sont remplis
            if (
                empty($postData['equipe1'])
                || empty($postData['equipe2'])
                || empty($postData['score'])
                || empty($postData['lieu'])
            ) {
                $logger->log('update_match_error', [
                    'error' => 'Missing match fields',
                    'match_id' => $match['match_id']
                ]);
                echo 'Tous les champs du match doivent être remplis.';
                return;
            }

            // ============================================
            // NETTOYAGE DES DONNÉES DU MATCH
            // ============================================

            $equipe1 = trim(strip_tags($postData['equipe1']));
            $equipe2 = trim(strip_tags($postData['equipe2']));
            $score = trim(strip_tags($postData['score']));
            $lieu = trim(strip_tags($postData['lieu']));
            $resume = trim(strip_tags($postData['resume'] ?? ''));  // ?? '' = valeur par défaut si vide

            // ============================================
            // MISE À JOUR DU MATCH DANS LA BASE DE DONNÉES
            // ============================================

            $insertMatch = $mysqlClient->prepare('
                UPDATE s2_resultats_sportifs
                SET equipe1 = :equipe1,
                    equipe2 = :equipe2,
                    score = :score,
                    lieu = :lieu,
                    resume = :resume
                WHERE id = :id_match
            ');

            $insertMatch->execute([
                'equipe1' => $equipe1,
                'equipe2' => $equipe2,
                'score' => $score,
                'lieu' => $lieu,
                'resume' => $resume,
                'id_match' => (int)$match['match_id']  // Conversion en entier pour sécurité
            ]);

            // LOGGING : succès de la mise à jour du match
            $logger->log('update_match_success', [
                'match_id' => $match['match_id'],
                'rows_affected' => $insertMatch->rowCount()
            ]);
        }
    }

} catch (Exception $e) {
    // ============================================
    // GESTION DES ERREURS
    // ============================================
    // Si une erreur survient dans le bloc try, on arrive ici

    // On enregistre l'erreur dans les logs
    $logger->log('update_error', [
        'error' => $e->getMessage(),  // Message d'erreur
        'id' => $id
    ]);

    echo "Une erreur est survenue lors de la modification.";
    return;
}
?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>article <?php echo ($id); ?> modifié</title>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
        rel="stylesheet">
</head>

<body class="d-flex flex-column min-vh-100">
    <div class="container">

        <h1>article <?php echo ($id); ?> modifié avec succès !</h1>

        <!-- Affichage des données modifiées dans une carte -->
        <div class="card">

            <div class="card-body">
                <h5 class="card-title"><?php echo ($titre); ?></h5>
                <p class="card-text"><?php echo $contenu; ?></p>
            </div>
        </div>
    </div>

    <!-- Bouton pour retourner à la page d'accueil -->
    <a class="btn btn-primary" role="button" href="./">RETOUR</a>
</body>

</html>
