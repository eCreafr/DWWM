<?php

/**
 * Point d'entrée unique de l'application (Front Controller)
 *
 * Dans une architecture MVC moderne, toutes les requêtes passent par ce fichier unique.
 * Le fichier .htaccess redirige toutes les URLs vers index.php qui :
 * 1. Charge la configuration
 * 2. Enregistre l'autoloader pour charger automatiquement les classes
 * 3. Instancie et appelle le Router qui détermine quelle action exécuter
 *
 * Avantages du Front Controller :
 * - Un seul point d'entrée facile à sécuriser et maintenir
 * - Configuration et initialisation centralisées
 * - Gestion cohérente des erreurs
 * - Permet l'utilisation d'URLs propres (SEO-friendly)
 */

// Charge la configuration générale (session, constantes, etc.)
require_once __DIR__ . '/../config/config.php';

// Charge l'autoloader qui se chargera d'inclure automatiquement toutes nos classes
require_once __DIR__ . '/../src/Autoloader.php';

// Enregistre l'autoloader auprès de PHP
// À partir de maintenant, PHP chargera automatiquement nos classes quand on les utilise
App\Autoloader::register();

// Instancie le Router qui va gérer le routage des requêtes
// Le Router analyse l'URL et appelle la bonne méthode du bon contrôleur
$router = new App\Router();

// Lance le routage : détermine quelle action exécuter et l'exécute
$router->dispatch();
