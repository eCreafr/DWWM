<?php

namespace App\Controllers;

use App\Models\User;
use App\Helpers\EmailValidator;
use App\Helpers\StringHelper;
use App\Helpers\UrlHelper;

/**
 * Classe RegisterController - Gère l'inscription des nouveaux utilisateurs
 *
 * Ce contrôleur s'occupe de :
 * - Afficher le formulaire d'inscription
 * - Valider les données saisies (email avec Egulias, mots de passe, etc.)
 * - Créer le compte utilisateur en base de données
 * - Connecter automatiquement l'utilisateur après inscription
 */
class RegisterController
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
        $this->userModel = new User();
    }

    /**
     * Affiche le formulaire d'inscription
     *
     * Cette méthode affiche simplement la vue du formulaire.
     * Si l'utilisateur est déjà connecté, on le redirige vers l'accueil.
     */
    public function showRegisterForm(): void
    {
        // Si l'utilisateur est déjà connecté, redirection vers l'accueil
        if (isset($_SESSION['user'])) {
            UrlHelper::redirect('home.html');
            return;
        }

        // Utilisation d'output buffering pour capturer le contenu de la vue
        ob_start();
        require_once __DIR__ . '/../Views/auth/register.php';
        $content = ob_get_clean();

        // Inclusion du layout avec le contenu
        require_once __DIR__ . '/../Views/layouts/default.php';
    }

    /**
     * Traite l'inscription d'un nouvel utilisateur
     *
     * Cette méthode :
     * 1. Vérifie que la requête est bien en POST
     * 2. Valide tous les champs du formulaire
     * 3. Vérifie que l'email n'existe pas déjà
     * 4. Crée l'utilisateur en base de données
     * 5. Connecte automatiquement l'utilisateur
     * 6. Redirige vers l'accueil avec message de succès
     */
    public function register(): void
    {
        // Vérifie que la requête est bien en POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            UrlHelper::redirect('register.html');
            return;
        }

        // Récupération et nettoyage des données du formulaire
        $name = isset($_POST['name']) ? StringHelper::sanitize($_POST['name']) : '';
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        // Tableau pour stocker les erreurs de validation
        $errors = [];

        // Validation du nom
        if (empty($name)) {
            $errors[] = 'Le nom est requis.';
        } elseif (strlen($name) < 2) {
            $errors[] = 'Le nom doit contenir au moins 2 caractères.';
        }

        // Validation de l'email avec Egulias (strict : RFC + DNS + MX)
        $emailValidation = EmailValidator::validateWithMessage($email);
        if (!$emailValidation['valid']) {
            $errors[] = $emailValidation['error'];
        }

        // Vérification que l'email n'existe pas déjà
        if (empty($errors) && $this->userModel->emailExists($email)) {
            $errors[] = 'Un compte existe déjà avec cette adresse email. Veuillez vous connecter ou utiliser une autre adresse.';
        }

        // Validation du mot de passe
        if (empty($password)) {
            $errors[] = 'Le mot de passe est requis.';
        } elseif (strlen($password) < 8) {
            $errors[] = 'Le mot de passe doit contenir au moins 8 caractères.';
        }

        // Validation de la confirmation du mot de passe
        if (empty($confirmPassword)) {
            $errors[] = 'La confirmation du mot de passe est requise.';
        } elseif ($password !== $confirmPassword) {
            $errors[] = 'Les mots de passe ne correspondent pas.';
        }

        // Si des erreurs ont été trouvées, on les stocke en session et on redirige
        if (!empty($errors)) {
            $_SESSION['error_message'] = implode('<br>', $errors);
            $_SESSION['form_data'] = [
                'name' => $name,
                'email' => $email
            ];
            UrlHelper::redirect('register.html');
            return;
        }

        // Préparation des données pour la création du compte
        $userData = [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role' => 'USER' // Rôle par défaut
        ];

        // Tentative de création de l'utilisateur
        $userId = $this->userModel->create($userData);

        // Si la création a échoué
        if (!$userId) {
            $_SESSION['error_message'] = 'Une erreur est survenue lors de la création du compte. Veuillez réessayer.';
            UrlHelper::redirect('register.html');
            return;
        }

        // Récupération des données complètes de l'utilisateur créé
        $user = $this->userModel->getById($userId);

        // Connexion automatique : stockage des informations en session
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role']
        ];

        // Message de succès
        $_SESSION['success_message'] = 'Vous êtes bien inscrit et actuellement connecté. Bienvenue ' . htmlspecialchars($user['name']) . ' !';

        // Nettoyage des données du formulaire stockées en session
        unset($_SESSION['form_data']);

        // Redirection vers la page d'accueil
        UrlHelper::redirect('home.html');
    }
}
