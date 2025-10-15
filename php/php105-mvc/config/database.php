<?php

/**
 * Fichier de configuration de la base de données
 *
 * Ce fichier contient tous les paramètres nécessaires pour se connecter
 * à la base de données MySQL
 */

// Paramètres de connexion à la base de données
return [
    'host' => 'localhost',           // Adresse du serveur MySQL
    'dbname' => 'sport_2000',        // Nom de la base de données
    'username' => 'root',             // Nom d'utilisateur MySQL
    'password' => '',                 // Mot de passe MySQL
    'charset' => 'utf8',              // Encodage des caractères
    'options' => [
        // Active le mode d'erreur par exception pour PDO
        // Ainsi, toute erreur SQL lancera une exception
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,

        // Définit le mode de récupération par défaut en tableau associatif
        // Chaque ligne sera retournée sous forme de tableau avec les noms de colonnes comme clés
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,

        // Désactive l'émulation des requêtes préparées
        // Utilise les vraies requêtes préparées de MySQL pour plus de sécurité
        PDO::ATTR_EMULATE_PREPARES => false,
    ],
];
