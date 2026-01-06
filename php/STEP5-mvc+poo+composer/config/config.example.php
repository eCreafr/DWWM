<?php

/**
 * Fichier de configuration général de l'application
 *
 * Ce fichier contient tous les paramètres globaux de l'application
 * comme l'URL de base, les paramètres de session, etc.
 *
 * INSTALLATION :
 * --------------
 * 1. Copiez .env.example vers .env et configurez vos variables d'environnement
 * 2. Ce fichier config.php sera créé automatiquement lors de l'installation
 * 3. Si vous le créez manuellement, copiez ce fichier vers config.php
 */

// Charge les variables d'environnement depuis le fichier .env
require_once __DIR__ . '/env.php';
loadEnv(__DIR__ . '/../.env');

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
// Chargé depuis la variable d'environnement TIMEZONE dans le fichier .env
date_default_timezone_set(env('TIMEZONE', 'UTC'));

// Clé API TinyMCE (éditeur de texte riche)
// Chargée depuis la variable d'environnement TINYMCE_API_KEY dans le fichier .env
// Obtenez une clé gratuite sur https://www.tiny.cloud/
define('TINYMCE_API_KEY', env('TINYMCE_API_KEY', 'no-api-key'));

// Active l'affichage des erreurs en mode développement
// Contrôlé par la variable d'environnement DEV_MODE dans le fichier .env
$devMode = filter_var(env('DEV_MODE', 'true'), FILTER_VALIDATE_BOOLEAN);
if ($devMode) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
}
