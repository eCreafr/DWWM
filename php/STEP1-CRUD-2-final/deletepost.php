<?php
// ============================================================
// PAGE DE TRAITEMENT DE SUPPRESSION - DELETEPOST.PHP
// ============================================================
// Cette page reçoit les données du formulaire delete.php
// et supprime définitivement l'article (et le match si demandé).
// Elle inclut aussi un système de logging.
// ============================================================

// Inclusion de la connexion à la base de données
include('db.php');





// ============================================================
// CLASSE JSONLOGGER : SYSTÈME DE JOURNALISATION (LOGGING)
// ============================================================
// Cette classe permet d'enregistrer toutes les suppressions dans un fichier JSON
// C'est très utile pour garder une trace des actions effectuées

class JsonLogger
{
    // Propriété privée : chemin du fichier de log
    private $logFile;

    // ============================================
    // CONSTRUCTEUR : appelé automatiquement lors de la création de l'objet
    // ============================================
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
    public function log($action, $data = [])
    {
        // Création d'une entrée de log avec toutes les informations importantes
        $logEntry = [
            'timestamp' => date('Y-m-d H:i:s'),     // Date et heure de l'action
            'action' => $action,                     // Type d'action (ex: 'delete_article')
            'data' => $data,                         // Données associées
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',           // Adresse IP de l'utilisateur
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown' // Navigateur utilisé
        ];

        // Lecture du fichier existant et décodage du JSON
        $logs = json_decode(file_get_contents($this->logFile), true) ?? [];

        // Ajout de la nouvelle entrée
        $logs[] = $logEntry;

        // Sauvegarde dans le fichier avec formatage lisible
        file_put_contents($this->logFile, json_encode($logs, JSON_PRETTY_PRINT));
    }
}

// Création d'une instance de JsonLogger
$logger = new JsonLogger();



// ============================================================
// RÉCUPÉRATION ET VALIDATION DES DONNÉES
// ============================================================

// Récupération des données du formulaire
$postData = $_POST;

// Vérification que l'ID existe et est un nombre
if (!isset($postData['id']) || !is_numeric($postData['id'])) {
    echo 'Il faut un identifiant valide pour supprimer un article.';
    return;
}

// ============================================================
// SUPPRESSION OPTIONNELLE DU MATCH
// ============================================================

// Si l'utilisateur a coché la case "supprimerMatch"
// On doit supprimer le match AVANT de supprimer l'article
// (car l'article contient la clé étrangère match_id)
if (isset($postData['supprimerMatch']) && $postData['supprimerMatch'] === 'on') {

    // ============================================
    // RÉCUPÉRATION DE L'ID DU MATCH
    // ============================================

    // On récupère d'abord le match_id depuis l'article
    $sqlQuery = '
        SELECT match_id
        FROM s2_articles_presse
        WHERE id = :id';

    $matchToDelete = $mysqlClient->prepare($sqlQuery);
    $matchToDelete->execute([
        'id' => (int)$postData['id']  // Conversion en entier pour sécurité
    ]);
    $match = $matchToDelete->fetch();

    // ============================================
    // SUPPRESSION DU MATCH
    // ============================================

    // Si un match existe (match_id n'est pas nul)
    if ($match && $match['match_id']) {

        // Requête DELETE pour supprimer le match
        $deleteMatch = $mysqlClient->prepare('DELETE FROM s2_resultats_sportifs WHERE id = :id_match');
        $deleteMatch->execute([
            'id_match' => (int)$match['match_id'],
        ]);
    }
}

// ============================================================
// SUPPRESSION DE L'ARTICLE
// ============================================================

// Maintenant on peut supprimer l'article en toute sécurité
// Requête DELETE pour supprimer l'article de la base de données
$deleteArticleStatement = $mysqlClient->prepare('DELETE FROM s2_articles_presse WHERE id = :id');
$deleteArticleStatement->execute([
    'id' => (int)$postData['id'],
]);


// ============================================================
// LOGGING : ENREGISTREMENT DE LA SUPPRESSION
// ============================================================

// On enregistre dans les logs que la suppression a réussi
$logger->log('delete_article_success', [
    'id' => (int)$postData['id'],  // ID de l'article supprimé
]);


?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Article <?php echo $postData['id']; ?> est supprimé</title>
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <!-- Message de confirmation de suppression -->
    <p>c'est supprimé, finito pour l'id #<?php echo $postData['id']; ?> </p> <br>

    <!-- Bouton pour retourner à la page d'accueil -->
    <a class="btn btn-primary" role="button" href="./">RETOUR</a>
</body>

</html>
