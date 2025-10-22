<?php

namespace App;

use App\Controllers\ArticleController;
use App\Controllers\AuthController;

/**
 * Classe Router - Gestionnaire de routes de l'application
 *
 * Le Router est responsable de :
 * - Analyser l'URL demandée par l'utilisateur
 * - Déterminer quelle action du contrôleur doit être exécutée
 * - Extraire les paramètres de l'URL (comme l'ID d'un article)
 * - Appeler la méthode appropriée du contrôleur
 *
 * Ce système remplace l'ancien système avec des fichiers PHP séparés pour chaque page.
 * Maintenant, tout passe par un point d'entrée unique (index.php) et le Router
 * décide quelle action exécuter.
 */
class Router
{
    /**
     * Instance du contrôleur d'articles
     * @var ArticleController
     */
    private ArticleController $articleController;

    /**
     * Instance du contrôleur d'authentification
     * @var AuthController
     */
    private AuthController $authController;

    /**
     * Constructeur - Initialise les contrôleurs
     */
    public function __construct()
    {
        $this->articleController = new ArticleController();
        $this->authController = new AuthController();
    }

    /**
     * Route la requête vers l'action appropriée
     *
     * Cette méthode analyse les paramètres GET de l'URL pour déterminer
     * quelle page afficher et quelle action exécuter.
     *
     * Exemples d'URLs gérées :
     * - home.html => page d'accueil
     * - articles/123-titre.html => affichage d'un article
     * - add.html => formulaire d'ajout
     * - addpost.html => traitement du formulaire d'ajout
     * - edit.html?id=123 => formulaire de modification
     * - editpost.html => traitement du formulaire de modification
     * - deletepost.html => traitement de la suppression
     * - login.html => formulaire de connexion
     * - loginpost.html => traitement de la connexion
     * - logout.html => déconnexion
     */
    public function dispatch(): void
    {
        // Récupère le paramètre 'page' de l'URL (défini par les règles .htaccess)
        $page = $_GET['page'] ?? 'home';

        // Liste des pages autorisées pour la sécurité
        // Cela évite qu'un utilisateur puisse accéder à n'importe quel fichier
        $allowedPages = [
            'home',
            'add',
            'addpost',
            'edit',
            'editpost',
            'delete',
            'deletepost',
            'articles',
            'login',
            'loginpost',
            'logout',
        ];

        // Route pour les articles individuels (ex: articles/123-titre.html)
        if ($page === 'articles' && isset($_GET['id']) && is_numeric($_GET['id'])) {
            // Appelle la méthode show() du contrôleur avec l'ID de l'article
            $this->articleController->show((int) $_GET['id']);
            return;
        }

        // Routes pour les autres pages selon le paramètre 'page'
        switch ($page) {
            case 'home':
                // Page d'accueil : liste de tous les articles
                $this->articleController->index();
                break;

            case 'add':
                // Formulaire d'ajout d'un article
                $this->articleController->create();
                break;

            case 'addpost':
                // Traitement du formulaire d'ajout (action POST)
                $this->articleController->store();
                break;

            case 'edit':
                // Formulaire de modification (nécessite un ID)
                if (isset($_GET['id']) && is_numeric($_GET['id'])) {
                    $this->articleController->edit((int) $_GET['id']);
                } else {
                    // Si pas d'ID ou ID invalide, erreur
                    $this->notFound();
                }
                break;

            case 'editpost':
                // Traitement du formulaire de modification (action POST)
                $this->articleController->update();
                break;

            case 'deletepost':
                // Traitement de la suppression (action POST)
                $this->articleController->delete();
                break;

            case 'login':
                // Formulaire de connexion
                $this->authController->showLoginForm();
                break;

            case 'loginpost':
                // Traitement du formulaire de connexion (action POST)
                $this->authController->login();
                break;

            case 'logout':
                // Déconnexion de l'utilisateur
                $this->authController->logout();
                break;

            default:
                // Page non trouvée : affiche une erreur 404
                $this->notFound();
                break;
        }
    }

    /**
     * Affiche une page d'erreur 404
     *
     * Cette méthode est appelée quand l'utilisateur demande une page
     * qui n'existe pas ou qui n'est pas dans la liste des pages autorisées
     */
    private function notFound(): void
    {
        // Définit le code de réponse HTTP 404 (Not Found)
        http_response_code(404);

        // Prépare les données pour la vue
        $title = "Page non trouvée";
        $metadesc = "La page demandée n'existe pas";

        // Charge la vue d'erreur 404
        extract(compact('title', 'metadesc'));
        ob_start();
        require_once __DIR__ . '/Views/errors/404.php';
        $content = ob_get_clean();
        require_once __DIR__ . '/Views/layouts/default.php';
    }
}
