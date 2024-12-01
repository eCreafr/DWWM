<?php

try {
    // On se connecte à MySQL
    $mysqlClient = new PDO('mysql:host=localhost;dbname=sport_2000;charset=utf8', 'root', '');
} catch (Exception $e) {
    // En cas d'erreur, on affiche un message et on arrête tout
    die('Erreur : ' . $e->getMessage());
}
