<?php
/**
 * Classe Admin (Modèle)
 * Représente un administrateur et gère l'authentification
 */
class Admin {

    private $db;
    private $table = 'admins';

    // Propriétés de l'administrateur
    public $id;
    public $login;
    public $motDePasse;
    public $dateCreation;

    /**
     * Constructeur : initialise la connexion à la base de données
     */
    public function __construct() {
        $database = Database::getInstance();
        $this->db = $database->getConnexion();
    }

    /**
     * Vérifie les identifiants de connexion
     * @param string $login Login de l'admin
     * @param string $motDePasse Mot de passe en clair
     * @return array|false Données de l'admin si succès, false sinon
     */
    public function verifierIdentifiants($login, $motDePasse) {
        $query = "SELECT * FROM " . $this->table . " WHERE login = :login LIMIT 1";
        $stmt = $this->db->prepare($query);

        // Nettoyage du login
        $login = htmlspecialchars(strip_tags($login));
        $stmt->bindParam(':login', $login);
        $stmt->execute();

        $admin = $stmt->fetch();

        // Vérifie si l'admin existe et si le mot de passe correspond
        if($admin && password_verify($motDePasse, $admin['motDePasse'])) {
            return $admin;
        }

        return false;
    }

    /**
     * Crée un nouvel administrateur
     * @return bool True si succès, false sinon
     */
    public function creer() {
        $query = "INSERT INTO " . $this->table . " (login, motDePasse) VALUES (:login, :motDePasse)";
        $stmt = $this->db->prepare($query);

        // Nettoyage des données
        $this->login = htmlspecialchars(strip_tags($this->login));

        // Hash du mot de passe
        $hashMotDePasse = password_hash($this->motDePasse, PASSWORD_DEFAULT);

        // Liaison des paramètres
        $stmt->bindParam(':login', $this->login);
        $stmt->bindParam(':motDePasse', $hashMotDePasse);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Récupère un admin par son ID
     * @param int $id ID de l'admin
     * @return array|false Données de l'admin ou false
     */
    public function lireUn($id) {
        $query = "SELECT id, login, dateCreation FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
}