<?php
/**
 * Classe AuthController
 * Gère l'authentification des administrateurs (login/logout)
 */
class AuthController extends BaseController {

    private $adminModel;

    /**
     * Constructeur : initialise le modèle Admin et démarre la session
     */
    public function __construct() {
        $this->adminModel = new Admin();
        $this->demarrerSession();
    }

    /**
     * Affiche le formulaire de connexion
     */
    public function login() {
        // Si déjà connecté, rediriger vers la liste des users
        if($this->estConnecte()) {
            $this->rediriger('user/index');
        }

        $donnees = [
            'titre' => 'Connexion administrateur'
        ];
        $this->chargerVue('auth/login', $donnees);
    }

    /**
     * Traite la soumission du formulaire de connexion
     */
    public function authentifier() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $login = $_POST['login'] ?? '';
            $motDePasse = $_POST['motDePasse'] ?? '';

            // Validation simple
            if(empty($login) || empty($motDePasse)) {
                $_SESSION['erreur'] = "Tous les champs sont obligatoires.";
                $this->rediriger('auth/login');
            }

            // Vérification des identifiants
            $admin = $this->adminModel->verifierIdentifiants($login, $motDePasse);

            if($admin) {
                // Connexion réussie : stockage des infos en session
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_login'] = $admin['login'];
                $_SESSION['connecte'] = true;

                // Redirection vers la liste des utilisateurs
                $this->rediriger('user/index');
            } else {
                // Échec de la connexion
                $_SESSION['erreur'] = "Identifiants incorrects.";
                $this->rediriger('auth/login');
            }
        }
    }

    /**
     * Déconnexion : détruit la session et redirige vers login
     */
    public function logout() {
        session_destroy();
        $this->rediriger('auth/login');
    }
}