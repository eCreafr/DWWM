
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boucles et tableaux</title>
</head>
<body>

while et tableaux

<?php

// tableau
$user1 = ['Mickaël Andrieu', 'email', 'S3cr3t', 34];

echo $user1[0]; // "Mickaël Andrieu"
echo $user1[1]; // "email"
echo $user1[3]; // 34

// tableau de tableau !

$mickael = ['Mickaël Andrieu', 'mickael.andrieu@exemple.com', 'S3cr3t', 34];
$mathieu = ['Mathieu Nebra', 'mathieu.nebra@exemple.com', 'devine', 33];
$laurene = ['Laurène Castor', 'laurene.castor@exemple.com', 'P4ssw0rD', 28];

$users = [$mickael, $mathieu, $laurene]; // tableau de tableau !

echo $users[1][1]; // "mathieu.nebra@exemple.com"


// avec boucle for each

foreach ($users as $value) {
    echo $value[0];
    echo $value[1];
    echo $value[2];
}


if (in_array($mathieu, $users))
{
    echo 'Mathieu fait bien partie des utilisateurs enregistrés !';
}

?>
<br><br>
<img src="img/012-3.webp" alt="" width="300px"> <br><br>
<?php
// while / boucle


$lines = 1;

while ($lines <= 200) { 
    echo ' '. $lines .' - j\'irais plus vite a copier 200 lignes en php. <br />';
    $lines++; // revient à ecrire $lines = $lines + 1
}






?>
    
</body>
</html>