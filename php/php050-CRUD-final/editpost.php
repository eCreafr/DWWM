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

// Log de début de tentative de modification
$logger->log('update_article_start', [
    'post_data' => $_POST
]);

// fin de l'initialisation du logger


$postData = $_POST;

// Vérifications initiales si on a des infos de base avnt de contiuer
if (
    !isset($postData['id'])
    || !is_numeric($postData['id'])
    || empty($postData['titre'])
    || empty($postData['contenu'])
    || trim(strip_tags($postData['titre'])) === ''
    || trim(strip_tags($postData['contenu'])) === ''
) {

    // Log d'erreur de validation
    $logger->log('update_article_error', [
        'error' => 'Validation failed',
        'post_data' => $postData
    ]);

    echo 'Il manque des informations pour permettre l\'édition du formulaire.';
    return;
}

// Mise à jour de l'article
$id = (int)$postData['id'];
$titre = trim(strip_tags($postData['titre']));
$contenu = trim(strip_tags($postData['contenu']));
try {
    // Log avant modification
    $logger->log('update_article_attempt', [
        'id' => $id,
        'titre' => $titre,
        'contenu_length' => strlen($contenu)
    ]);

    // Mise à jour de l'article
    $insertcontenuStatement = $mysqlClient->prepare('UPDATE s2_articles_presse SET titre = :titre, contenu = :contenu WHERE id = :id');
    $insertcontenuStatement->execute([
        'titre' => $titre,
        'contenu' => $contenu,
        'id' => $id,
    ]);

    // Log du succès de la mise à jour de l'article
    $logger->log('update_article_success', [
        'id' => $id,
        'rows_affected' => $insertcontenuStatement->rowCount()
    ]);

    // Modification du match si demandé
    if (isset($postData['modifierMatch']) && $postData['modifierMatch'] === 'on') {
        $logger->log('update_match_attempt', [
            'article_id' => $id
        ]);

        // Récupération de l'id du match associé
        $sqlQuery = 'SELECT match_id FROM s2_articles_presse WHERE id = :id';
        $matchStatement = $mysqlClient->prepare($sqlQuery);
        $matchStatement->execute([
            'id' => $id
        ]);
        $match = $matchStatement->fetch();

        if ($match && $match['match_id']) {
            // Vérification des champs du match
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

            // Nettoyer les données du match
            $equipe1 = trim(strip_tags($postData['equipe1']));
            $equipe2 = trim(strip_tags($postData['equipe2']));
            $score = trim(strip_tags($postData['score']));
            $lieu = trim(strip_tags($postData['lieu']));
            $resume = trim(strip_tags($postData['resume'] ?? ''));

            // Mise à jour du match
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
                'id_match' => (int)$match['match_id']
            ]);

            $logger->log('update_match_success', [
                'match_id' => $match['match_id'],
                'rows_affected' => $insertMatch->rowCount()
            ]);
        }
    }
} catch (Exception $e) {
    $logger->log('update_error', [
        'error' => $e->getMessage(),
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

        <div class="card">

            <div class="card-body">
                <h5 class="card-title"><?php echo ($titre); ?></h5>
                <p class="card-text"><?php echo $contenu; ?></p>
            </div>
        </div>
    </div>
    <a class="btn btn-primary" role="button" href="./">RETOUR</a>
</body>

</html>