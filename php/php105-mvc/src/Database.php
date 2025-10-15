<?php

namespace App;

use PDO;
use PDOException;

/**
 * Classe Database - Gestion de la connexion à la base de données
 *
 * Cette classe implémente le pattern Singleton pour garantir qu'une seule
 * connexion à la base de données existe pendant toute l'exécution du script.
 *
 * Le pattern Singleton est utile ici car :
 * - Il évite de créer plusieurs connexions inutiles à la base de données
 * - Il permet d'accéder à la connexion depuis n'importe où dans l'application
 * - Il économise les ressources du serveur
 */
class Database
{
    /**
     * Instance unique de la classe (pattern Singleton)
     * @var Database|null
     */
    private static ?Database $instance = null;

    /**
     * Objet PDO représentant la connexion à la base de données
     * @var PDO|null
     */
    private ?PDO $connection = null;

    /**
     * Constructeur privé pour empêcher l'instanciation directe
     *
     * Le constructeur est privé car on veut forcer l'utilisation
     * de la méthode getInstance() pour obtenir l'instance unique
     */
    private function __construct()
    {
        // Le constructeur ne fait rien, la connexion se fait à la demande (lazy loading)
    }

    /**
     * Empêche le clonage de l'instance (partie du pattern Singleton)
     *
     * Si on autorisait le clone, on pourrait créer plusieurs instances,
     * ce qui casserait le principe du Singleton
     */
    private function __clone()
    {
    }

    /**
     * Empêche la désérialisation de l'instance (partie du pattern Singleton)
     *
     * Cela évite de créer une nouvelle instance en désérialisant un objet
     */
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize singleton");
    }

    /**
     * Retourne l'instance unique de Database (pattern Singleton)
     *
     * Si l'instance n'existe pas encore, elle est créée.
     * Sinon, on retourne l'instance existante.
     *
     * @return Database L'instance unique de la classe
     */
    public static function getInstance(): Database
    {
        // Si l'instance n'existe pas encore, on la crée
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Établit et retourne la connexion PDO à la base de données
     *
     * Cette méthode utilise le lazy loading : la connexion n'est établie
     * que lorsqu'on en a vraiment besoin, pas avant.
     *
     * @return PDO L'objet PDO de connexion
     * @throws PDOException Si la connexion échoue
     */
    public function getConnection(): PDO
    {
        // Si la connexion existe déjà, on la retourne directement
        if ($this->connection !== null) {
            return $this->connection;
        }

        try {
            // Charge les paramètres de connexion depuis le fichier de config
            $config = require __DIR__ . '/../config/database.php';

            // Construction de la chaîne DSN (Data Source Name) pour PDO
            // Format: "mysql:host=localhost;dbname=sport_2000;charset=utf8"
            $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";

            // Création de la connexion PDO
            $this->connection = new PDO(
                $dsn,
                $config['username'],
                $config['password'],
                $config['options']
            );

            return $this->connection;
        } catch (PDOException $e) {
            // En cas d'erreur, on affiche un message et on arrête le script
            die('Erreur de connexion à la base de données : ' . $e->getMessage());
        }
    }
}
