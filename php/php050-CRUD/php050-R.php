<?php

echo '<h1>étape 2 / on affiche une boucle de news contenues dans la BDD avec un SQL qui LEFT JOIN</h1>';

include('php050connect.php');
// Si tout va bien, on peut continuer :

// On récupère tout le contenu de la table sport articles
$sqlQuery = '
SELECT a.id, a.titre, a.contenu, a.date_publication, r.score, r.lieu
FROM s2_articles_presse a
LEFT JOIN s2_resultats_sportifs r ON a.match_id = r.id 
ORDER BY `a`.`date_publication` 
DESC;';
$newsFraiches = $mysqlClient->prepare($sqlQuery);
$newsFraiches->execute();
$news = $newsFraiches->fetchAll();


// Tronquer fonction
function truncateString($string, $length = 20)
{
    if (strlen($string) > $length) {
        return substr($string, 0, $length) . ' (...)';
    }
    return $string;
}
// On affiche chaque recette une à une
foreach ($news as $new) {


?>

    <p>-<?php echo $new['date_publication']; ?> <br><strong><?php echo truncateString($new['titre'], 20); ?> :</strong><br><strong style="color:#FF0000"> <?php echo $new['score']; ?></strong> (lieu : <?php echo $new['lieu']; ?>)<br>
        <br>
        <?php echo truncateString($new['contenu'], 100); ?>


        <br><br> <a href="php050tp2article.php?id=<?php echo $new['id']; ?>">lire la suite</a>
    </p><br><br>


<?php
}
?>