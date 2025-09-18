<?php

try {
    // On se connecte à MySQL avec PDO (on voit ça dans 2 semaines)
    $mysqlClient = new PDO('mysql:host=localhost;dbname=sport_2000;charset=utf8', 'lateste33260', '123456');
} catch (Exception $e) {
    // En cas d'erreur, on affiche un message et on arrête tout
    die('Erreur : ' . $e->getMessage());
}
