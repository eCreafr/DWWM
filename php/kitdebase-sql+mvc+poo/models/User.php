<?php
/**
 * Classe User (Modèle)
 * Représente un utilisateur et gère les opérations CRUD
 * CRUD = Create (Créer), Read (Lire), Update (Modifier), Delete (Supprimer)
 */
class User {

    private $db; // Connexion à la base de données
    private $table = 'users'; // Nom de la table

    // Propriétés de l'utilisateur (correspondent aux colonnes de la table)
    public $id;
    public $nom;
    public $email;
    public $dateCreation;

    /**
     * Constructeur : initialise la connexion à la base de données
     */
    public function __construct() {
        $database = Database::getInstance();
        $this->db = $database->getConnexion();
    }

    /**
     * Récupère tous les utilisateurs
     * @return array Tableau d'utilisateurs
     */
    public function lireTous() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY id DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Récupère un utilisateur par son ID
     * @param int $id ID de l'utilisateur
     * @return array|false Données de l'utilisateur ou false
     */
    public function lireUn($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * Crée un nouvel utilisateur
     * @return bool True si succès, false sinon
     */
    public function creer() {
        $query = "INSERT INTO " . $this->table . " (nom, email) VALUES (:nom, :email)";
        $stmt = $this->db->prepare($query);

        // Nettoyage des données (sécurité)
        $this->nom = htmlspecialchars(strip_tags($this->nom));
        $this->email = htmlspecialchars(strip_tags($this->email));

        // Liaison des paramètres
        $stmt->bindParam(':nom', $this->nom);
        $stmt->bindParam(':email', $this->email);

        // Exécution de la requête
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Modifie un utilisateur existant
     * @return bool True si succès, false sinon
     */
    public function modifier() {
        $query = "UPDATE " . $this->table . " SET nom = :nom, email = :email WHERE id = :id";
        $stmt = $this->db->prepare($query);

        // Nettoyage des données
        $this->nom = htmlspecialchars(strip_tags($this->nom));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Liaison des paramètres
        $stmt->bindParam(':nom', $this->nom);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

        // Exécution
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Supprime un utilisateur
     * @param int $id ID de l'utilisateur à supprimer
     * @return bool True si succès, false sinon
     */
    public function supprimer($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}