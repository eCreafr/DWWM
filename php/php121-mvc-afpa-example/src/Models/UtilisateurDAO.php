<?php

namespace Afpa\Models;

use Afpa\Tools\Database;

use PDO;

/**
 * Classe à utiliser pour requêter la base de données pour récupérer les informations des utilisateurs.
 */
class UtilisateurDAO {

    /**
     * Permet d'obtenir un tableau de tous les utilisateurs.
     */
    public static function getAll(): array {
        // récupération de l'objet de requêtage
        $pdo = Database::connect();

        // instanciation d'un tableau de stagaires
        $utilisateurs = array();
        $sql = "SELECT * from utilisateur";

        $results = $pdo->query($sql);

        // boucle sur toutes les lignes de résultat
        foreach ($results as $row_result) {
            $utilisateur = self::createUserFromRow($row_result);
            
            // ajout de l'utilisateur au tableau d'utilisateurs
            array_push($utilisateurs, $utilisateur);
        }

        // déconnexion de la base de données
        Database::disconnect();
        return $utilisateurs;
    }

    /**
     * Permet d'obtenir un utilisateur par son identifiant
     */
    public static function getById(int $id): Utilisateur {
        // récupération de l'objet de requêtage
        $pdo = Database::connect();
        $statement = $pdo->prepare("SELECT * from utilisateur WHERE :id");

        // association des paramètres sur la requête
        $statement->bindParam("id", PDO::PARAM_INT);
        $statement->execute();

        // récupération du résultat
        $row_result = $statement->fetch();

        // instanciation de l'utilisateur
        $utilisateur = self::createUserFromRow($row_result);

        // déconnexion de la base de données
        Database::disconnect();
        return $utilisateur;
    }

    /**
     * Retourne l'utilisateur en fonction  
     */
    public static function getByEmail(string $email): ?Utilisateur {

        $pdo = Database::connect();

        $statement = $pdo->prepare("select * from utilisateur where email LIKE :email");

        $statement->bindParam("email", $email);

        $statement->execute();
        // récupération de l'enregistrement de l'utliisateur
        $row_result = $statement->fetch();
        
        // intialisation de la variable qui va contenir une instance d'utilisateur
        $utilisateur = null;
        if ($row_result != false)
        {
            // instanciation de l'utilisateur en fonction d'une ligne d'enregistrement
            $utilisateur = self::createUserFromRow($row_result);    
        }

        // déconnexion de la base de données
        Database::disconnect();
        return $utilisateur;
    }

    /**
     * Instancie un utilsateur à partir d'un enregistrement sous la forme d'un tableau associatif
     * Attention, il faut que le tableau associatif soit complet
     * 
     * @param $array_row L'enregistrement provenant de la BDD stocké dans un tableau associatif
     */
    private static function createUserFromRow(array $row_result): Utilisateur {

        // récupération du résultat à partir du tableau
        $id = $row_result['id'];
        $prenom = $row_result['prenom'];
        $nom = $row_result['nom'];
        $email = $row_result['email'];
        $password = $row_result['password'];

        // instanciation de l'utilisateur
        $utilisateur = new Utilisateur($id, $prenom, $nom, $email, $password);

        return $utilisateur;
    }
}