<?php

try {
    // On se connecte à MySQL
    $mysqlClient = new PDO('mysql:host=localhost;dbname=sport_2000;charset=utf8', 'root', '');
} catch (Exception $e) {
    // En cas d'erreur, on affiche un message et on arrête tout
    die('Erreur : ' . $e->getMessage());
}
// Si tout va bien, on peut continuer :

// On récupère tout le contenu de la table sport articles
$sqlQuery = '
SELECT a.titre, a.contenu, a.date_publication, r.score, r.lieu
FROM s2_articles_presse a
JOIN s2_resultats_sportifs r ON a.match_id = r.id 
ORDER BY `a`.`date_publication` 
DESC; ';
$newsFraiches = $mysqlClient->prepare($sqlQuery);
$newsFraiches->execute();
$news = $newsFraiches->fetchAll();


// Tronquer fonction
function truncateString($string, $length = 20)
{
    if (strlen($string) > $length) {
        return substr($string, 0, $length) . '...';
    }
    return $string;
}
// On affiche chaque recette une à une
foreach ($news as $new) {


?>

    <p>-<?php echo $new['date_publication']; ?> <strong><?php echo $new['titre']; ?> :</strong><strong style="color:#FF0000"> <?php echo $new['score']; ?></strong> (lieu : <?php echo $new['lieu']; ?>)
        <br><?php echo truncateString($new['contenu'], 99); ?>(...) <a href="#">lire la suite</a>
    </p><br><br>


<?php
}
?>