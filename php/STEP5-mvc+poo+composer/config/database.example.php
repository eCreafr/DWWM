<?php
/**
 * Configuration de la base de données - FICHIER EXEMPLE
 *
 * INSTRUCTIONS D'INSTALLATION :
 * 1. Copiez ce fichier et renommez-le en "database.php"
 * 2. Modifiez les valeurs ci-dessous selon votre configuration MySQL
 * 3. Ne committez JAMAIS le fichier database.php (il est dans .gitignore)
 *
 * Exemple de commande pour créer la base :
 * mysql -u root -p -e "CREATE DATABASE sport_2000 CHARACTER SET utf8 COLLATE utf8_general_ci;"
 */

return [
    // Hôte de la base de données (généralement 'localhost')
    'host' => 'localhost',

    // Nom de la base de données
    'dbname' => 'sport_2000',

    // Nom d'utilisateur MySQL
    'username' => 'root',

    // Mot de passe MySQL (laissez vide '' pour WAMP/XAMPP par défaut)
    'password' => '',

    // Encodage de caractères
    'charset' => 'utf8',

    // Options PDO
    'options' => [
        // Mode d'erreur : exception en cas d'erreur SQL
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,

        // Mode de récupération par défaut : tableau associatif
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,

        // Désactive l'émulation des requêtes préparées (plus sécurisé)
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
];
