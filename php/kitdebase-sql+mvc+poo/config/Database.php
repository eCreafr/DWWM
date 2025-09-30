<?php
/**
 * Classe Database
 * Gère la connexion à la base de données avec PDO
 * Utilise le pattern Singleton (une seule instance)
 */
class Database {

    // Instance unique de la connexion (Singleton)
    private static $instance = null;
    private $connexion;

    /**
     * Constructeur privé (empêche la création directe d'objets)
     */
    private function __construct() {
        try {
            // Chaîne de connexion DSN (Data Source Name)
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;

            // Options PDO pour la connexion
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Active les exceptions en cas d'erreur
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Mode de récupération par défaut (tableau associatif)
                PDO::ATTR_EMULATE_PREPARES => false // Désactive l'émulation des requêtes préparées
            ];

            // Création de la connexion PDO
            $this->connexion = new PDO($dsn, DB_USER, DB_PASS, $options);

        } catch(PDOException $e) {
            // En cas d'erreur, affichage du message
            die("Erreur de connexion : " . $e->getMessage());
        }
    }

    /**
     * Méthode pour obtenir l'instance unique de Database (Singleton)
     * @return Database Instance unique
     */
    public static function getInstance() {
        if(self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Retourne l'objet de connexion PDO
     * @return PDO Connexion à la base de données
     */
    public function getConnexion() {
        return $this->connexion;
    }

    /**
     * Empêche le clonage de l'instance
     */
    private function __clone() {}

    /**
     * Empêche la désérialisation de l'instance
     */
    public function __wakeup() {
        throw new Exception("Impossible de désérialiser un singleton");
    }
}