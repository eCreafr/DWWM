<?php

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


// Récupérer l'ID du dernier article supprimé
$lastId = $postData['id'];

// Stocker le message de succès en session
$_SESSION['success_message'] = "L'article a été définitivement supprimé !";

// Rediriger vers edit.php avec l'ID
header('Location: home.html?id=' . $lastId);
