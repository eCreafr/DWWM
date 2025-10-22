<?php

namespace App\Helpers;

/**
 * Classe AuthHelper - Fonctions utilitaires pour l'authentification
 *
 * Cette classe contient des méthodes statiques (helpers) pour faciliter
 * la gestion de l'authentification et des autorisations dans toute l'application.
 *
 * Les méthodes statiques permettent de les utiliser sans créer d'instance :
 * if (AuthHelper::isLoggedIn()) { ... }
 *
 * Cette classe centralise toute la logique d'authentification :
 * - Vérifier si un utilisateur est connecté
 * - Vérifier si c'est un administrateur
 * - Récupérer les informations de l'utilisateur connecté
 * - Vérifier les autorisations
 */
class AuthHelper
{
    /**
     * Vérifie si un utilisateur est connecté
     *
     * Cette méthode vérifie si une session utilisateur existe.
     * La session est créée lors de la connexion dans AuthController::login()
     *
     * @return bool true si l'utilisateur est connecté, false sinon
     */
    public static function isLoggedIn(): bool
    {
        // Vérifie si la variable de session 'user' existe et n'est pas vide
        return isset($_SESSION['user']) && !empty($_SESSION['user']);
    }

    /**
     * Vérifie si l'utilisateur connecté est un administrateur
     *
     * Cette méthode est utile pour conditionner l'affichage de certaines fonctionnalités
     * ou restreindre l'accès à certaines pages.
     *
     * @return bool true si l'utilisateur est admin, false sinon
     */
    public static function isAdmin(): bool
    {
        // Vérifie d'abord si l'utilisateur est connecté
        if (!self::isLoggedIn()) {
            return false;
        }

        // Vérifie si le rôle de l'utilisateur est 'ADMIN'
        return isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'ADMIN';
    }

    /**
     * Vérifie si l'utilisateur connecté est un simple utilisateur
     *
     * @return bool true si l'utilisateur a le rôle USER, false sinon
     */
    public static function isUser(): bool
    {
        // Vérifie d'abord si l'utilisateur est connecté
        if (!self::isLoggedIn()) {
            return false;
        }

        // Vérifie si le rôle de l'utilisateur est 'USER'
        return isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'USER';
    }

    /**
     * Récupère les informations de l'utilisateur connecté
     *
     * Cette méthode retourne toutes les informations de l'utilisateur stockées en session.
     * Utile pour afficher le nom de l'utilisateur, son email, etc.
     *
     * @return array|null Les données de l'utilisateur ou null si non connecté
     */
    public static function getCurrentUser(): ?array
    {
        // Retourne les données de l'utilisateur si connecté, sinon null
        return self::isLoggedIn() ? $_SESSION['user'] : null;
    }

    /**
     * Récupère l'ID de l'utilisateur connecté
     *
     * @return int|null L'ID de l'utilisateur ou null si non connecté
     */
    public static function getCurrentUserId(): ?int
    {
        // Si l'utilisateur est connecté et a un ID, on le retourne
        return self::isLoggedIn() && isset($_SESSION['user']['id'])
            ? (int) $_SESSION['user']['id']
            : null;
    }

    /**
     * Récupère le nom de l'utilisateur connecté
     *
     * @return string|null Le nom de l'utilisateur ou null si non connecté
     */
    public static function getCurrentUserName(): ?string
    {
        // Si l'utilisateur est connecté et a un nom, on le retourne
        return self::isLoggedIn() && isset($_SESSION['user']['name'])
            ? $_SESSION['user']['name']
            : null;
    }

    /**
     * Récupère le rôle de l'utilisateur connecté
     *
     * @return string|null Le rôle (ADMIN ou USER) ou null si non connecté
     */
    public static function getCurrentUserRole(): ?string
    {
        // Si l'utilisateur est connecté et a un rôle, on le retourne
        return self::isLoggedIn() && isset($_SESSION['user']['role'])
            ? $_SESSION['user']['role']
            : null;
    }

    /**
     * Requiert qu'un utilisateur soit connecté
     *
     * Cette méthode force la connexion : si l'utilisateur n'est pas connecté,
     * il est redirigé vers la page de login.
     *
     * Utile pour protéger des pages qui nécessitent une authentification.
     * Exemple d'utilisation dans un contrôleur :
     *   AuthHelper::requireLogin();
     *
     * @param string $redirectUrl L'URL de redirection (par défaut login.html)
     */
    public static function requireLogin(string $redirectUrl = 'login.html'): void
    {
        // Si l'utilisateur n'est pas connecté
        if (!self::isLoggedIn()) {
            // Stocke un message d'erreur
            $_SESSION['error_message'] = 'Vous devez être connecté pour accéder à cette page.';

            // Redirige vers la page de login
            UrlHelper::redirect($redirectUrl);
            exit; // Arrête l'exécution du script
        }
    }

    /**
     * Requiert que l'utilisateur soit administrateur
     *
     * Cette méthode vérifie que l'utilisateur est connecté ET qu'il est admin.
     * Sinon, il est redirigé vers la page d'accueil avec un message d'erreur.
     *
     * Utile pour protéger des pages réservées aux administrateurs.
     * Exemple d'utilisation dans un contrôleur :
     *   AuthHelper::requireAdmin();
     *
     * @param string $redirectUrl L'URL de redirection (par défaut home.html)
     */
    public static function requireAdmin(string $redirectUrl = 'home.html'): void
    {
        // Vérifie d'abord si l'utilisateur est connecté
        if (!self::isLoggedIn()) {
            $_SESSION['error_message'] = 'Vous devez être connecté pour accéder à cette page.';
            UrlHelper::redirect('login.html');
            exit;
        }

        // Vérifie si l'utilisateur est admin
        if (!self::isAdmin()) {
            $_SESSION['error_message'] = 'Accès refusé. Cette page est réservée aux administrateurs.';
            UrlHelper::redirect($redirectUrl);
            exit;
        }
    }
}
