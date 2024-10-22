<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
<link rel="stylesheet" href="../html/css/froggie.css" /> 
</head>
<body>
<div>
<div>
<strong>Afficher un message, du html avec echo</strong><br><br>
<?php     // echo :
    echo("coucou");  // on affiche coucou dans le html
    echo("<br><strong>coucou</strong>"); // on affiche du code html  ?>
<br><br>

<strong>Une variable:</strong> <br><br>
    <?php
    


    // on defini des variables  

$client = "user";            //on charge le nom d'une variable dans une variable
$$client = "bob";            //on charge la valeur "bob" dans la variable $user

// on appelle ces variabkes avec echo de differente façon :
echo("<br>$user<br>");        //affiche bob
echo($$client);            //affiche bob
echo("<br>");
echo("$$client <br>");        //affiche $user
echo("{${$client }}<br>");    //affiche bob
echo("{${'user'}}<br>");  

?><br><br>
<strong>Le nom d'une variable:</strong> <br><br>
<ul>
    <li>a comme préfixe le signe $</li>
    <li>est sensible à la casse</li>
    <li>ne contient jamais de caractères accentués</li>
</ul>
<br><br>
<strong>Les differentes variables : </strong><br><br>
<?php
	$var0 = 33;
	$var1 = 33.500;
	$var2 = "La Teste";
	$var3 = true;
	$var4 = Null;
	$var5 = array("Manon","Julien","Sebastien","Ayman","Paul","Thomas");
	var_dump($var0,$var1,$var2,$var3,$var4,$var5);
?>
</div>

<div>


<br><br>


$_GET	Il contient les valeurs passées par url ou par la méthode GET d'un formulaire.
Les noms des champs de formulaire sont les clés de ce tableau.<br><br>
$_POST	Il contient les valeurs passées par la méthode POST d'un formulaire.
Les noms des champs de formulaire sont les clés de ce tableau.<br><br>
$_SERVER il contient les informations sur le serveur , la navigateur, version de php, etc
<br><br>

<strong>Ici une fonction va afficher le navigateur client et son système d'exploitation</strong> <br><br>


<?php echo $_SERVER['PHP_SELF'];


function getBrowserAndOS() {
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $browser = "Inconnu";
    $os = "Inconnu";

    // Détection du navigateur
    if (preg_match('/MSIE/i', $user_agent) && !preg_match('/Opera/i', $user_agent)) {
        $browser = 'Internet Explorer';
    } elseif (preg_match('/Firefox/i', $user_agent)) {
        $browser = 'Mozilla Firefox';
    } elseif (preg_match('/Chrome/i', $user_agent)) {
        $browser = 'Google Chrome';
    } elseif (preg_match('/Safari/i', $user_agent)) {
        $browser = 'Apple Safari';
    } elseif (preg_match('/Opera/i', $user_agent)) {
        $browser = 'Opera';
    } elseif (preg_match('/Netscape/i', $user_agent)) {
        $browser = 'Netscape';
    }

    // Détection du système d'exploitation
    if (preg_match('/windows|win32/i', $user_agent)) {
        $os = 'Windows';
    } elseif (preg_match('/macintosh|mac os x/i', $user_agent)) {
        $os = 'MacOS';
    } elseif (preg_match('/linux/i', $user_agent)) {
        $os = 'Linux';
    }

    return "Navigateur : $browser, Système d'exploitation : $os";
}

echo getBrowserAndOS();

?>
<br><br>
<strong>Ici une fonction va afficher l'adresse IP du navigateur client</strong> <br><br>

<?php 

function getIp() {
  if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
  } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
  } else {
    $ip = $_SERVER['REMOTE_ADDR'];
  }
  return $ip;
}

echo "Votre adresse IP est : " . getIp();
 ?>

</div>

</div>
</body>
</html>