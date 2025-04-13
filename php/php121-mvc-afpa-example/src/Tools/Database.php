<?php

namespace Afpa\Tools;

use PDO;
use PDOException;

/**
 * Encapsule les informations à la connexion à la BDD.
 * A utiliser dès que l'on souhaite effectuer une requête.
 */
class Database
{
    private static string $dsn;
    private static string $db_user;
    private static string $db_password;

    /**
     * Attribut stockant la connexion à PDO.
     * "null" à l'état initial
     */
    private static ?PDO $connexion = null;

    private function __construct()
    {
        die("Appel au constructeur non autorisé, passez par 'connect()'");
    }

    /**
     * Crée et renvoie une connexion PDO
     */
    public static function connect(): PDO
    {
        // si aucune connexion (cas de l'état initial) on instancie le PDO
        if (null == self::$connexion) {
            try {
                // utilisation des variables d'environnement
                self::$dsn = $_ENV['DATABASE_DSN'];
                self::$db_user = $_ENV['DATABASE_USER'];
                self::$db_password = $_ENV['DATABASE_PASSWORD'];

                // instanciation d'un objet de la classe PDO
                self::$connexion = new PDO(self::$dsn, self::$db_user, self::$db_password);
            
            } catch (PDOException $e) {
                die($e->getMessage());
            }
        }
        return self::$connexion;
    }

    /**
     * Supprime la connexion PDO (revient à une déconnexion de la base de données)
     */
    public static function disconnect()
    {
        self::$connexion = null;
    }
}
