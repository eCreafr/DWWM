<?php // condition if elseif else
?>


<?php

$etat = "digestion";

if ($etat === "digestion") {
    echo ("c'est l'heure de la sieste");
}
?>

<br><br>

<?php
$etat = "digestion";

if ($etat === "digestion") {
    echo ("pff je suis en pleine $etat!");
} else {
    echo ("je ne suis pas en $etat");
}
?>


<br><br><br><br><br><br><br><br><br>


<?php
$etat = "fatigué";

if ($etat === "digestion") {
    echo ("Hourra, je suis $etat!");

} elseif ($etat === "fatigué") {
    echo ("Oups, je suis $etat");

} elseif ($etat === "en pleine forme") {
    echo ("Whou, je suis $etat");

} else {
    echo ("Bon, je suis $etat");
}
?>


<?php // condition switch
?>


<br><br><br><br>
<?php

$pays = "FR";


switch ($pays) {
    case "NL":
        echo "bienvenue en Hollande";
        break;
    case "IT":
        echo "bienvenue en Italie";
        break;
    case "D":
        echo "bienvenue en Allemagne";
        break;
    case "B":
        echo "bienvenue en Belgique";
        break;
    case "FR":
        echo "bienvenue en France";
        break;
    default:
        echo "Veuillez choisir un pays reconnu par le programme";
        break;
}
?>
<br><br><br>

<?php


$prenom = "Sophie";


switch ($prenom) {
    case "Manon":
    case "Julien":
    case "Paul":
    case "Thomas":
        echo "vous êtes en DWWM";
        break;
    case "Ayman":
        echo "Vous êtes peut etre en DWWM";
        break;
    case "Mohamed":
    case "Sullivan":
    case "Céline":
    case "Léa":
    case "Maxime":
        echo "Vous êtes en CDUI";
        break;
    default:
        echo "Vous n'êtes pas a l'afpa";
        break;
}
?>