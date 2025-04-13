<?php

namespace Afpa;

use Afpa\Core\Router;

use Afpa\Controllers\HomeController;
use Afpa\Controllers\ArticleController;
use Afpa\Controllers\LoginController;
use Afpa\Controllers\UtilisateurController;

// Instanciation du router
$router = new Router();

// Définition de toutes les routes du site
$router->addRoute('/', HomeController::class, 'index');
$router->addRoute('/login', LoginController::class, 'login');
$router->addRoute('/articles/list', ArticleController::class, 'index');
    
// page administrateur
$router->addRoute('/utilisateurs/list', UtilisateurController::class, 'list');