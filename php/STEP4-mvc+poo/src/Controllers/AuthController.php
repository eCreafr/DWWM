<?php

namespace App\Controllers;

use App\Models\User;
use App\Helpers\UrlHelper;

/**
 * Classe AuthController - Contrôleur gérant l'authentification
 *
 * Ce contrôleur s'occupe de toutes les actions liées à l'authentification :
 * - Affichage du formulaire de connexion
 * - Traitement de la connexion (vérification des identifiants)
 * - Déconnexion (destruction de la session)
 *
 * Dans le pattern MVC :
 * - Le contrôleur reçoit les requêtes de l'utilisateur
 * - Il fait appel au modèle User pour vérifier les identifiants
 * - Il stocke les informations de l'utilisateur en session
 * - Il redirige vers les bonnes pages selon le résultat
 */
class AuthController
{
    /**
     * Instance du modèle User
     * @var User
     */
    private User $userModel;

    /**
     * Constructeur - Initialise le modèle User
     */
    public function __construct()
    {
        // On instancie le modèle User pour pouvoir interroger la base de données
        $this->userModel = new User();
    }

    /**
     * Affiche le formulaire de connexion
     *
     * Cette méthode affiche simplement la page de login avec le formulaire.
     */
    public function showLoginForm(): void
    {
        // Prépare les métadonnées de la page
        $title = "Connexion";
        $metadesc = "Connectez-vous pour accéder à votre espace";

        // Affiche la vue du formulaire de connexion
        $this->render('auth/login', compact('title', 'metadesc'));
    }

    /**
     * Traite la soumission du formulaire de connexion
     *
     * Cette méthode :
     * 1. Récupère les données du formulaire (email et mot de passe)
     * 2. Valide les données
     * 3. Vérifie les identifiants avec le modèle User
     * 4. Si les identifiants sont corrects, crée une session utilisateur
     * 5. Redirige vers la page d'accueil ou affiche une erreur
     */
    public function login(): void
    {
        // Vérifie que la requête est bien en POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            UrlHelper::redirect('login.html');
            return;
        }

        // Récupère les données du formulaire
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        // Validation : vérifie que les champs ne sont pas vides
        if (empty($email) || empty($password)) {
            // Stocke le message d'erreur en session
            $_SESSION['error_message'] = 'Veuillez remplir tous les champs.';

            // Redirige vers le formulaire de connexion
            UrlHelper::redirect('login.html');
            return;
        }

        // Appelle le modèle User pour vérifier les identifiants
        // La méthode authenticate() retourne les données de l'utilisateur ou false
        $user = $this->userModel->authenticate($email, $password);

        // Si les identifiants sont incorrects (authenticate retourne false)
        if (!$user) {
            // Stocke le message d'erreur en session
            $_SESSION['error_message'] = 'Email ou mot de passe incorrect.';

            // Redirige vers le formulaire de connexion
            UrlHelper::redirect('login.html');
            return;
        }

        // Si les identifiants sont corrects, on crée la session utilisateur
        // On stocke les informations importantes de l'utilisateur en session
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role'], // ADMIN ou USER
        ];

        // Message de succès personnalisé selon le rôle
        if ($user['role'] === 'ADMIN') {
            $_SESSION['success_message'] = "Bienvenue {$user['name']} ! Vous êtes connecté en tant qu'administrateur.";
        } else {
            $_SESSION['success_message'] = "Bienvenue {$user['name']} ! Vous êtes connecté.";
        }

        // Redirige vers la page d'accueil
        UrlHelper::redirect('home.html');
    }

    /**
     * Déconnecte l'utilisateur
     *
     * Cette méthode détruit la session de l'utilisateur et le déconnecte.
     * Elle supprime toutes les données de session et redirige vers la page de connexion.
     */
    public function logout(): void
    {
        // Supprime toutes les variables de session
        // Cela déconnecte l'utilisateur en effaçant ses informations
        session_unset();

        // Détruit complètement la session
        // Cela supprime le fichier de session sur le serveur
        session_destroy();

        // Message de confirmation de déconnexion
        // On redémarre la session juste pour pouvoir afficher ce message
        session_start();
        $_SESSION['success_message'] = 'Vous avez été déconnecté avec succès.';

        // Redirige vers le formulaire de connexion
        UrlHelper::redirect('login.html');
    }

    /**
     * Méthode utilitaire pour rendre une vue
     *
     * Cette méthode centralise le chargement des vues.
     * Elle inclut le layout (header/footer) et la vue demandée.
     *
     * @param string $view Le chemin de la vue (ex: 'auth/login')
     * @param array $data Les données à passer à la vue
     */
    private function render(string $view, array $data = []): void
    {
        // Extract transforme les clés du tableau en variables
        // ['title' => 'Test'] devient $title = 'Test'
        extract($data);

        // Démarre la capture de sortie (output buffering)
        // Tout ce qui sera affiché sera stocké en mémoire au lieu d'être envoyé
        ob_start();

        // Inclut le fichier de vue
        require_once __DIR__ . "/../Views/{$view}.php";

        // Récupère le contenu capturé et le stocke dans $content
        $content = ob_get_clean();

        // Inclut le layout qui affichera $content
        require_once __DIR__ . '/../Views/layouts/default.php';
    }
}
