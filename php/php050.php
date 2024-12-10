<?php

include('php050connect.php');
// Si tout va bien, on peut continuer :

// On récupère tout le contenu de la table sport articles
$sqlQuery = '
SELECT titre, date_publication, contenu FROM `s2_articles_presse` ORDER BY `date_publication` DESC;';
$newsFraiches = $mysqlClient->prepare($sqlQuery);
$newsFraiches->execute();
$news = $newsFraiches->fetchAll();

// On affiche chaque recette une à une
foreach ($news as $new) {

    // Tronquer à 150 caractères
    $truncatedContent = substr($new['contenu'], 0, 150) . '...';

?>
    <p> <strong><?php echo $new['titre']; ?></strong><br>
        (<?php echo $new['date_publication']; ?>) <br><?php echo $truncatedContent; ?> <a href="#">lire la suite</a></p><br><br>



<?php
}
?>