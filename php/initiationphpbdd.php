<?php

// set up nos variables BDD :
$host = 'localhost';
$user = 'root';
$password = 'root';  // laissez vide sur WAMP
$dbname = 'afpa';

$connexion = mysqli_connect($host, $user, $password, $dbname);

if (!$connexion){
    die('Erreur connexion entre la chaise et le clavier je penses : ' .mysqli_connect_error());
}

$requete = 'SELECT `prenom` FROM `stagiaires`';
$resultat = mysqli_query($connexion, $requete);

if (!$resultat){
    die('Erreur requête entre la chaise et le clavier je penses : ' .mysqli_error($connexion));
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta http-equiv="refresh" content="5">
    <title>Ma premiere connexion et requete SQL</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 2rem; }
        h1   { color: #129800; }
        ul   { list-style: disc; padding-left: 1.5rem; }
        li   { padding: .3rem 0; font-size: 1.1rem; }
    </style>
</head>
<body>
    

    <h1>Prénoms des stagiaires Afpa</h1>


<?php if (mysqli_num_rows($resultat) === 0) : ?>

    <p>Nope, déso, ya personne a l'afpa aujourd'hui</p>


<?php else : ?>
    <ul>
        <?php while ($ligne = mysqli_fetch_assoc($resultat)) : ?>
        
            <li><?= htmlspecialchars($ligne['prenom']); ?></li>
          
        <?php endwhile; ?>
    </ul>
<?php endif; ?>

</body>
</html>

<?php 
mysqli_free_result($resultat);
mysqli_close($connexion);
?>