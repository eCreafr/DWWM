<?php

namespace App\Models;

use App\Database;
use PDO;

/**
 * Classe User - Modèle représentant un utilisateur
 *
 * Cette classe gère toutes les opérations liées aux utilisateurs :
 * - Récupération des utilisateurs depuis la base de données
 * - Vérification des identifiants lors de la connexion
 * - Gestion des rôles (ADMIN ou USER)
 *
 * Dans le pattern MVC, le Modèle s'occupe uniquement de la manipulation des données.
 * Il ne contient ni logique métier complexe, ni code HTML.
 */
class User
{
    /**
     * Instance de la connexion à la base de données
     * @var PDO
     */
    private PDO $db;

    /**
     * Constructeur - Initialise la connexion à la base de données
     */
    public function __construct()
    {
        // Récupère l'instance unique de Database et sa connexion PDO
        // On utilise le pattern Singleton de Database pour obtenir
        // la connexion unique à la base de données
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Récupère tous les utilisateurs
     *
     * @return array Tableau contenant tous les utilisateurs
     */
    public function getAll(): array
    {
        // Prépare la requête SQL
        $query = $this->db->prepare('SELECT id, name, email, role FROM users ORDER BY id DESC');

        // Exécute la requête
        $query->execute();

        // Retourne tous les résultats sous forme de tableau associatif
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère un utilisateur par son ID
     *
     * @param int $id L'identifiant de l'utilisateur
     * @return array|false Les données de l'utilisateur ou false si non trouvé
     */
    public function getById(int $id): array|false
    {
        // Prépare la requête avec un paramètre nommé :id
        $query = $this->db->prepare('SELECT id, name, email, role FROM users WHERE id = :id');

        // Lie le paramètre :id à la valeur $id
        $query->bindValue(':id', $id, PDO::PARAM_INT);

        // Exécute la requête
        $query->execute();

        // Retourne une seule ligne (ou false si aucun résultat)
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère un utilisateur par son email
     *
     * Cette méthode est utile lors de la connexion pour vérifier si l'email existe
     *
     * @param string $email L'adresse email de l'utilisateur
     * @return array|false Les données de l'utilisateur ou false si non trouvé
     */
    public function getByEmail(string $email): array|false
    {
        // Prépare la requête avec un paramètre nommé :email
        $query = $this->db->prepare('SELECT id, name, email, pswd, role FROM users WHERE email = :email');

        // Lie le paramètre :email à la valeur $email
        $query->bindValue(':email', $email, PDO::PARAM_STR);

        // Exécute la requête
        $query->execute();

        // Retourne une seule ligne (ou false si aucun résultat)
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Vérifie les identifiants de connexion
     *
     * Cette méthode est appelée lors de la tentative de connexion.
     * Elle vérifie que l'email et le mot de passe correspondent à un utilisateur.
     *
     * ATTENTION : Dans un vrai projet, les mots de passe doivent être hashés !
     * Exemple : password_hash() pour créer et password_verify() pour vérifier
     * Ici on utilise du texte brut uniquement à des fins pédagogiques.
     *
     * @param string $email L'adresse email saisie
     * @param string $password Le mot de passe saisi
     * @return array|false Les données de l'utilisateur si connexion réussie, false sinon
     */
    public function authenticate(string $email, string $password): array|false
    {
        // Récupère l'utilisateur par son email
        $user = $this->getByEmail($email);

        // Si l'utilisateur n'existe pas, retourne false
        if (!$user) {
            return false;
        }

        // Vérifie le mot de passe
        // IMPORTANT : Dans un vrai projet, il faudrait utiliser password_verify()
        // Exemple : if (password_verify($password, $user['pswd'])) { ... }
        if ($user['pswd'] === $password) {
            // Supprime le mot de passe des données retournées (sécurité)
            unset($user['pswd']);

            // Retourne les informations de l'utilisateur (sans le mot de passe)
            return $user;
        }

        // Si le mot de passe ne correspond pas, retourne false
        return false;
    }

    /**
     * Vérifie si un utilisateur est administrateur
     *
     * @param array $user Les données de l'utilisateur
     * @return bool true si l'utilisateur est admin, false sinon
     */
    public function isAdmin(array $user): bool
    {
        // Vérifie si le rôle de l'utilisateur est 'ADMIN'
        return isset($user['role']) && $user['role'] === 'ADMIN';
    }

    /**
     * Vérifie si un utilisateur est un simple utilisateur
     *
     * @param array $user Les données de l'utilisateur
     * @return bool true si l'utilisateur a le rôle USER, false sinon
     */
    public function isUser(array $user): bool
    {
        // Vérifie si le rôle de l'utilisateur est 'USER'
        return isset($user['role']) && $user['role'] === 'USER';
    }
}
