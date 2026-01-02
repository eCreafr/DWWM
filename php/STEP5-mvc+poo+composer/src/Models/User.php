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
        $query = $this->db->prepare('SELECT id, name, email, pswd, role, two_factor_secret, two_factor_enabled FROM users WHERE email = :email');

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

        // Vérifie le mot de passe avec "password_verify" 

        if (password_verify($password, $user['pswd'])) {
            // puis supprime le mot de passe des données retournées (sécurité)
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

    /**
     * Crée un nouvel utilisateur dans la base de données
     *
     * Cette méthode est utilisée lors de l'inscription d'un nouveau membre.
     * Le mot de passe est automatiquement hashé avec password_hash().
     * Le rôle par défaut est 'USER'.
     *
     * @param array $data Tableau associatif contenant 'name', 'email', 'password'
     * @return int|false L'ID du nouvel utilisateur créé ou false en cas d'échec
     */
    public function create(array $data): int|false
    {
        // Hash le mot de passe avec l'algorithme bcrypt (sécurisé)
        // PASSWORD_DEFAULT utilise bcrypt, qui évolue automatiquement
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

        // Prépare la requête d'insertion avec des paramètres nommés
        $query = $this->db->prepare(
            'INSERT INTO users (name, email, pswd, role)
             VALUES (:name, :email, :pswd, :role)'
        );

        // Lie les paramètres aux valeurs
        $query->bindValue(':name', $data['name'], PDO::PARAM_STR);
        $query->bindValue(':email', $data['email'], PDO::PARAM_STR);
        $query->bindValue(':pswd', $hashedPassword, PDO::PARAM_STR);
        $query->bindValue(':role', $data['role'] ?? 'USER', PDO::PARAM_STR); // Rôle par défaut : USER

        // Exécute la requête
        if ($query->execute()) {
            // Retourne l'ID du dernier enregistrement inséré
            return (int) $this->db->lastInsertId();
        }

        // Retourne false si l'insertion a échoué
        return false;
    }

    /**
     * Vérifie si un email existe déjà dans la base de données
     *
     * Cette méthode est utile lors de l'inscription pour éviter les doublons.
     *
     * @param string $email L'adresse email à vérifier
     * @return bool true si l'email existe déjà, false sinon
     */
    public function emailExists(string $email): bool
    {
        // Prépare la requête avec un paramètre nommé :email
        $query = $this->db->prepare('SELECT COUNT(*) FROM users WHERE email = :email');

        // Lie le paramètre :email à la valeur $email
        $query->bindValue(':email', $email, PDO::PARAM_STR);

        // Exécute la requête
        $query->execute();

        // Retourne true si le count est > 0 (l'email existe)
        return $query->fetchColumn() > 0;
    }

    /**
     * Active le 2FA pour un utilisateur
     *
     * @param int $userId ID de l'utilisateur
     * @param string $secret Secret 2FA
     * @return bool true si succès, false sinon
     */
    public function enableTwoFactor(int $userId, string $secret): bool
    {
        $query = $this->db->prepare(
            'UPDATE users SET two_factor_secret = :secret, two_factor_enabled = 1 WHERE id = :id'
        );

        $query->bindValue(':secret', $secret, PDO::PARAM_STR);
        $query->bindValue(':id', $userId, PDO::PARAM_INT);

        return $query->execute();
    }

    /**
     * Désactive le 2FA pour un utilisateur
     *
     * @param int $userId ID de l'utilisateur
     * @return bool true si succès, false sinon
     */
    public function disableTwoFactor(int $userId): bool
    {
        $query = $this->db->prepare(
            'UPDATE users SET two_factor_secret = NULL, two_factor_enabled = 0 WHERE id = :id'
        );

        $query->bindValue(':id', $userId, PDO::PARAM_INT);

        return $query->execute();
    }

    /**
     * Vérifie si un utilisateur a le 2FA activé
     *
     * @param int $userId ID de l'utilisateur
     * @return bool true si 2FA activé, false sinon
     */
    public function hasTwoFactorEnabled(int $userId): bool
    {
        $query = $this->db->prepare('SELECT two_factor_enabled FROM users WHERE id = :id');
        $query->bindValue(':id', $userId, PDO::PARAM_INT);
        $query->execute();

        return (bool) $query->fetchColumn();
    }

    /**
     * Récupère le secret 2FA d'un utilisateur
     *
     * @param int $userId ID de l'utilisateur
     * @return string|null Secret 2FA ou null
     */
    public function getTwoFactorSecret(int $userId): ?string
    {
        $query = $this->db->prepare('SELECT two_factor_secret FROM users WHERE id = :id');
        $query->bindValue(':id', $userId, PDO::PARAM_INT);
        $query->execute();

        $result = $query->fetchColumn();
        return $result ?: null;
    }
}
