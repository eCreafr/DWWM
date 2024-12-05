<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP 004 | Get & Post</title>
    
<link rel="stylesheet" href="../html/css/froggie.css" /> 
    <style>.cadre{ border: #000 solid 1px; 
    margin:10px;
    padding:10px;}
    
    .spacer{height:25vh;}</style>
</head>
<body>
    <br><br>
<div class="cadre">

    <h1>$_GET</h1>
   <p>$_GET	Il contient les valeurs passées par url ou par la méthode GET d'un formulaire.
Les noms des champs de formulaire sont les clés de ce tableau.<br><br>
en savoir plus sur la doc officielle : <a href="
https://www.php.net/manual/fr/reserved.variables.get.php">
https://www.php.net/manual/fr/reserved.variables.get.php</a></p>

<p>
    
<?php

if (isset($_GET["name"]) && $_GET["name"] !== '') { //on détermine ici si la variable est déclaré, si elle est differente de vide (null)

echo '<h2>Bonjour ' . htmlspecialchars($_GET["name"]) . '!</h2>'; //on récupère le contenu de name et on l'affiche en charactere HTML

} else {
    echo '<strong>cliquez sur le lien :</strong>'; //en cas d'echec on invite a cliquer sur le lien
}
?>

</p>
<a href="http://localhost:3000/php/php004.php?name=Sullivan%20range%20ta%20chambre">
http://localhost:3000/php/php004.php?name=Sullivan%20range%20ta%20chambre

</a>
<br><br>nb : parler des abus possible avec cette méthode vulnérable à l'interception et à la manipulation et de htmlspecialchars</div>

<!--  
<div+style%3D"color%3A%23FF0000"><strong>Alerte%20!</strong>Sébastien%20il%20vous%20reste%202%20jours%20pour%20verser%20les%20derniers%20500€%20<br>qui%20garantissent%20votre%20certification%20DWWWM,%20<br>merci%20d%27appeler%20le%2006%2000%2000%2000%2000%20pour%20le%20dernier%20versement,<br>%20ou%20<a%20href="https://www.paypal.com/paypalme/ecrea/500">cliquez%20ici</a></div>
-->

<br><div class="froggiesplaining">
      <span> Froggiesplaining :</span>
<br><img src="img/004-1.png" alt="">

      <img src="../html/img/froggie-300.png" alt="Froggie" class="overfrog" />
    </div><br>  <div class="spacer"></div><div class="cadre">

<h1>$_POST</h1>
<p>    


$_POST	Il contient les valeurs passées par la méthode POST d'un formulaire.
Les noms des champs de formulaire sont les clés de ce tableau.</p><p> en savoir plus sur la doc officielle : 
    <a href="https://www.php.net/manual/fr/reserved.variables.post.php">https://www.php.net/manual/fr/reserved.variables.post.php</a></p>
<br>














<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["name"]) && $_POST["name"] !== '') {
    echo '<h2>Bonjour ' . htmlspecialchars($_POST["name"]) . '!</h2>';
} else {
    echo '<strong>Veuillez fournir un nom :</strong>';
}
?>

  <form action="php004.php" method="POST">
        <label for="name">Votre nom :</label>
        <input type="text" id="name" name="name" required>
        <button type="submit">Envoyer</button>
    </form>






<br><br></div>


<div class="froggiesplaining">
      <span> Froggiesplaining :</span>
<br>
<img src="img/004-2.png" alt="">

      <img src="../html/img/froggie-300.png" alt="Froggie" class="overfrog" />
    </div>

    <div class="spacer"></div>

<div class="cadre">
<h1>$_SERVER</h1>

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
        $browser = 'Google Chrome ou assimilé chromium';
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

<div class="froggiesplaining">
      <span> Froggiesplaining :</span>
<br>
<img src="img/004-3.png" alt="">

      <img src="../html/img/froggie-300.png" alt="Froggie" class="overfrog" />
    </div>

</body>
</html>
