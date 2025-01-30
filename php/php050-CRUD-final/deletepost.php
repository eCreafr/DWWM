<?php
include('db.php');





// Initialisation du logger
class JsonLogger
{
    private $logFile;

    public function __construct($filename = 'articles_logs.json')
    {
        $this->logFile = $filename;
        if (!file_exists($this->logFile)) {
            file_put_contents($this->logFile, '[]');
        }
    }

    public function log($action, $data = [])
    {
        $logEntry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'action' => $action,
            'data' => $data,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
        ];

        $logs = json_decode(file_get_contents($this->logFile), true) ?? [];
        $logs[] = $logEntry;
        file_put_contents($this->logFile, json_encode($logs, JSON_PRETTY_PRINT));
    }
}

$logger = new JsonLogger();



// fin de l'initialisation du logger







$postData = $_POST;

if (!isset($postData['id']) || !is_numeric($postData['id'])) {
    echo 'Il faut un identifiant valide pour supprimer un article.';
    return;
}

// D'abord, on retrouve l'id du match (s'il y en a) avant de supprimer l'article correspondant
if (isset($postData['supprimerMatch']) && $postData['supprimerMatch'] === 'on') {
    $sqlQuery = '
        SELECT match_id
        FROM s2_articles_presse 
        WHERE id = :id';

    $matchToDelete = $mysqlClient->prepare($sqlQuery);
    $matchToDelete->execute([
        'id' => (int)$postData['id']
    ]);
    $match = $matchToDelete->fetch();

    if ($match && $match['match_id']) {
        $deleteMatch = $mysqlClient->prepare('DELETE FROM s2_resultats_sportifs WHERE id = :id_match');
        $deleteMatch->execute([
            'id_match' => (int)$match['match_id'],
        ]);
    }
}

// Ensuite, on supprime l'article
$deleteArticleStatement = $mysqlClient->prepare('DELETE FROM s2_articles_presse WHERE id = :id');
$deleteArticleStatement->execute([
    'id' => (int)$postData['id'],
]);


// Log du succès de la mise à jour de l'article
$logger->log('delete_article_success', [
    'id' => (int)$postData['id'],
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
    <p>c'est supprimé, finito pour l'id #<?php echo $postData['id']; ?> </p> <br>

    <a class="btn btn-primary" role="button" href="./">RETOUR</a>
</body>

</html>