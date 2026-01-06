<?php

/**
 * Fichier de configuration général de l'application
 *
 * Ce fichier contient tous les paramètres globaux de l'application
 * comme l'URL de base, les paramètres de session, etc.
 *
 * INSTALLATION :
 * --------------
 * 1. Copiez ce fichier et renommez-le en "config.php"
 * 2. Modifiez les paramètres selon votre environnement
 * 3. Ajoutez votre clé API TinyMCE (gratuite sur https://www.tiny.cloud/)
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
// TEMPORAIRE : UTC pour correspondre au téléphone en développement
// En production : utiliser 'Europe/Paris'
date_default_timezone_set('UTC');

// Clé API TinyMCE (éditeur de texte riche)
// Obtenez une clé gratuite sur https://www.tiny.cloud/auth/signup/
// Remplacez 'no-api-key' par votre clé API pour débloquer toutes les fonctionnalités
define('TINYMCE_API_KEY', 'no-api-key');

// Active l'affichage des erreurs en mode développement
// En production, mettre ces valeurs à 0 et 'off'
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
