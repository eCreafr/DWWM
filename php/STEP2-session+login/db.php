<?php
/*
 * Fichier de connexion à la base de données
 * =========================================
 *
 * Ce fichier est responsable de l'établissement de la connexion à MySQL.
 * Il utilise PDO (PHP Data Objects) qui est l'interface recommandée pour
 * interagir avec une base de données en PHP moderne.
 */

try {
    /*
     * Création d'une instance PDO pour se connecter à MySQL
     *
     * Les paramètres de connexion :
     * - host=localhost : le serveur MySQL est sur la même machine
     * - dbname=sport_2000 : nom de la base de données
     * - charset=utf8 : encodage pour supporter les accents et caractères spéciaux
     *
     * Les identifiants (nom d'utilisateur et mot de passe) suivent
     *
     * ⚠️ ATTENTION : En production, ne JAMAIS mettre les identifiants en dur dans le code !
     * Utilisez plutôt des variables d'environnement ou un fichier de configuration sécurisé.
     */
    $mysqlClient = new PDO('mysql:host=localhost;dbname=sport_2000;charset=utf8', 'lateste33260', '123456');
} catch (Exception $e) {
    /*
     * Gestion des erreurs de connexion
     *
     * Si la connexion échoue (serveur inaccessible, identifiants incorrects, etc.),
     * on capture l'exception et on arrête le script avec die().
     *
     * En production, on pourrait :
     * - Afficher un message générique à l'utilisateur
     * - Logger l'erreur dans un fichier
     * - Rediriger vers une page d'erreur personnalisée
     */
    die('Erreur : ' . $e->getMessage());
}
