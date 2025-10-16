<?php
/**
 * Classe UserController
 * Gère les actions liées aux utilisateurs
 * Hérite de BaseController pour accéder aux méthodes communes
 */
class UserController extends BaseController {

    private $userModel;

    /**
     * Constructeur : initialise le modèle User et vérifie l'authentification
     */
    public function __construct() {
        $this->userModel = new User();
        $this->verifierAuthentification();
    }

    /**
     * Action par défaut : affiche la liste des utilisateurs
     */
    public function index() {
        // Récupère tous les utilisateurs depuis le modèle
        $users = $this->userModel->lireTous();

        // Prépare les données pour la vue
        $donnees = [
            'titre' => 'Liste des utilisateurs',
            'users' => $users
        ];

        // Charge la vue avec les données
        $this->chargerVue('users/index', $donnees);
    }

    /**
     * Affiche le formulaire de création d'utilisateur
     */
    public function creer() {
        $donnees = [
            'titre' => 'Créer un utilisateur'
        ];
        $this->chargerVue('users/formulaire', $donnees);
    }

    /**
     * Traite la création d'un nouvel utilisateur (soumission du formulaire)
     */
    public function enregistrer() {
        // Vérifie que la requête est de type POST
        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Assigne les valeurs du formulaire au modèle
            $this->userModel->nom = $_POST['nom'] ?? '';
            $this->userModel->email = $_POST['email'] ?? '';

            // Validation simple
            if(empty($this->userModel->nom) || empty($this->userModel->email)) {
                die("Tous les champs sont obligatoires.");
            }

            // Tente de créer l'utilisateur
            if($this->userModel->creer()) {
                // Redirige vers la liste si succès
                $this->rediriger('user/index');
            } else {
                die("Erreur lors de la création de l'utilisateur.");
            }
        }
    }

    /**
     * Affiche le formulaire de modification d'un utilisateur
     * @param int $id ID de l'utilisateur à modifier
     */
    public function modifier($id) {
        // Récupère l'utilisateur depuis la base
        $user = $this->userModel->lireUn($id);

        if(!$user) {
            die("Utilisateur non trouvé.");
        }

        $donnees = [
            'titre' => 'Modifier un utilisateur',
            'user' => $user
        ];

        $this->chargerVue('users/formulaire', $donnees);
    }

    /**
     * Traite la modification d'un utilisateur (soumission du formulaire)
     */
    public function mettreAJour() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $this->userModel->id = $_POST['id'] ?? 0;
            $this->userModel->nom = $_POST['nom'] ?? '';
            $this->userModel->email = $_POST['email'] ?? '';

            // Validation
            if(empty($this->userModel->nom) || empty($this->userModel->email)) {
                die("Tous les champs sont obligatoires.");
            }

            // Tente de modifier
            if($this->userModel->modifier()) {
                $this->rediriger('user/index');
            } else {
                die("Erreur lors de la modification.");
            }
        }
    }

    /**
     * Supprime un utilisateur
     * @param int $id ID de l'utilisateur à supprimer
     */
    public function supprimer($id) {
        if($this->userModel->supprimer($id)) {
            $this->rediriger('user/index');
        } else {
            die("Erreur lors de la suppression.");
        }
    }
}