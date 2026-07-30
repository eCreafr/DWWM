<?php
declare(strict_types=1);

/**
 * ===================================================================
 * AMORCE MVC — FRONT CONTROLLER (démonstration guidée 15h45)
 * ===================================================================
 * Point d'entrée UNIQUE de l'application. Toutes les requêtes passent
 * par ici.
 *
 * Pourquoi un point d'entrée unique ? À faire verbaliser :
 *   - un seul endroit où charger la config, la session, l'autoload
 *   - un seul endroit où appliquer les contrôles de sécurité
 *   - les fichiers sources sortent de la racine web (dossier /src),
 *     donc inaccessibles directement par URL
 *
 * Ce dernier point est majeur : seul /public est exposé au navigateur.
 */

require_once __DIR__ . '/../src/Database.php';
require_once __DIR__ . '/../src/Repository/DocumentRepository.php';
require_once __DIR__ . '/../src/Controller/DocumentController.php';

// --- Routage minimal ---
// En 45 minutes, on ne construit pas un routeur. On montre le PRINCIPE :
// l'URL détermine quelle méthode du contrôleur est appelée.

$action = $_GET['action'] ?? 'liste';

$controller = new DocumentController();

match ($action) {
    'liste'  => $controller->liste(),
    'detail' => $controller->detail(),
    default  => $controller->liste(),
};
