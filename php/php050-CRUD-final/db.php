<?php
// ============================================================
// FICHIER DE CONNEXION À LA BASE DE DONNÉES
// ============================================================
// Ce fichier est inclus dans toutes les pages qui ont besoin
// d'accéder à la base de données MySQL.
// ============================================================

try {
    // On se connecte à MySQL avec PDO (PHP Data Objects)
    // PDO est une extension PHP qui permet de se connecter à différentes bases de données
    // et offre une meilleure sécurité contre les injections SQL

    // Paramètres de connexion :
    // - host=localhost : le serveur MySQL est sur la même machine
    // - dbname=sport_2000 : nom de la base de données à utiliser
    // - charset=utf8 : encodage des caractères (permet les accents français)
    // - 'root' : nom d'utilisateur MySQL (par défaut en local)
    // - '' : mot de passe vide (par défaut en local, À CHANGER EN PRODUCTION !)
    $mysqlClient = new PDO('mysql:host=localhost;dbname=sport_2000;charset=utf8', 'root', '');

} catch (Exception $e) {
    // En cas d'erreur de connexion (base de données inexistante, mauvais identifiants, etc.)
    // on affiche un message d'erreur et on arrête complètement l'exécution du script
    die('Erreur : ' . $e->getMessage());
}
