<?php

/**
 * Fichier de configuration général de l'application
 *
 * Ce fichier contient tous les paramètres globaux de l'application
 * comme l'URL de base, les paramètres de session, etc.
 */

// Démarrage de la session pour pouvoir gérer les messages flash et l'authentification
session_start();

// Détection automatique de l'URL de base de l'application
// Cela permet de rendre l'application portable sur différents environnements
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'];
$baseFolder = dirname($_SERVER['SCRIPT_NAME']);

// Constante contenant l'URL de base complète (ex: http://localhost/git/php/php105-mvc/public)
define('BASE_URL', $protocol . $host . $baseFolder);

// Définit le fuseau horaire par défaut pour les dates
date_default_timezone_set('Europe/Paris');

// Active l'affichage des erreurs en mode développement
// En production, mettre ces valeurs à 0 et 'off'
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
