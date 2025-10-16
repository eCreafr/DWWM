<?php

/**
 * Fichier index.php - Point d'entrée de l'application (Routeur)
 * Toutes les requêtes passent par ce fichier grâce au .htaccess
 */

// Active l'affichage des erreurs (en développement uniquement)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Charge la configuration
require_once __DIR__ . '/config/config.php';

// Charge la classe Database
require_once __DIR__ . '/config/Database.php';

// Charge le contrôleur de base
require_once __DIR__ . '/controllers/BaseController.php';

// Charge tous les modèles (on pourrait utiliser un autoloader)
require_once __DIR__ . '/models/User.php';
require_once __DIR__ . '/models/Admin.php';

// Charge tous les contrôleurs
require_once __DIR__ . '/controllers/UserController.php';
require_once __DIR__ . '/controllers/AuthController.php';

/**

 * ROUTEUR SIMPLE
 * Analyse l'URL et appelle le bon contrôleur/action

 */

// Récupère l'URL demandée (après le nom de domaine)
// Exemple : http://localhost/user/modifier/5
// Donne : user/modifier/5
$url = isset($_GET['url']) ? $_GET['url'] : 'user/index';
$url = rtrim($url, '/'); // Enlève le slash final
$url = filter_var($url, FILTER_SANITIZE_URL); // Nettoie l'URL
$url = explode('/', $url); // Sépare en tableau

// Décomposition de l'URL
// $url[0] = contrôleur (ex: user)
// $url[1] = action (ex: modifier)
// $url[2] = paramètre (ex: 5)

$controllerName = isset($url[0]) && !empty($url[0]) ? ucfirst($url[0]) . 'Controller' : 'UserController';
$action = isset($url[1]) && !empty($url[1]) ? $url[1] : 'index';
$params = isset($url[2]) ? $url[2] : null;

// Vérifie si le contrôleur existe
if (!class_exists($controllerName)) {
    die("Erreur : Le contrôleur '$controllerName' n'existe pas.");
}

// Instancie le contrôleur
$controller = new $controllerName();

// Vérifie si la méthode (action) existe
if (!method_exists($controller, $action)) {
    die("Erreur : L'action '$action' n'existe pas dans le contrôleur '$controllerName'.");
}

// Appelle la méthode avec ou sans paramètres
if ($params !== null) {
    $controller->$action($params);
} else {
    $controller->$action();
}
