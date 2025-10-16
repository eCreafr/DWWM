<?php
/*
 * Traitement du formulaire de connexion - submit-login.php
 * =========================================================
 *
 * Ce fichier traite les données soumises par le formulaire de connexion.
 * Il vérifie les identifiants et connecte l'utilisateur si tout est correct.
 *
 * ⚠️ IMPORTANT : Ce fichier ne contient PAS de HTML, c'est un fichier de traitement pur.
 * Après traitement, il redirige toujours vers index.php.
 *
 * 📚 Concepts abordés :
 * - Traitement de formulaire POST
 * - Validation des données
 * - Authentification simple (sans hachage de mot de passe)
 * - Stockage d'informations en session
 * - Redirection après traitement
 *
 * ⚠️ SÉCURITÉ : Cette méthode est PÉDAGOGIQUE uniquement !
 * En production, il faudrait :
 * - Hasher les mots de passe avec password_hash()
 * - Utiliser password_verify() pour la vérification
 * - Limiter les tentatives de connexion
 * - Protéger contre les attaques par force brute
 */

// Démarrage de la session pour stocker les informations de connexion
session_start();

// Inclusion de la connexion à la base de données
require(__DIR__ . '/db.php');

// Inclusion des fonctions (notamment redirectToUrl et la liste des abonnés)
include(__DIR__ . '/functions.php');


// ============================================================================
// RÉCUPÉRATION DES DONNÉES DU FORMULAIRE
// ============================================================================

/*
 * $_POST contient toutes les données envoyées par le formulaire
 * On les copie dans $postData pour plus de lisibilité
 */
$postData = $_POST;


// ============================================================================
// VALIDATION ET AUTHENTIFICATION
// ============================================================================

/*
 * Vérification que les champs requis sont bien présents
 * isset() vérifie si une variable existe et n'est pas null
 */
if (isset($postData['email']) && isset($postData['mdp'])) {

    // ÉTAPE 1 : Validation du format de l'email
    if (!filter_var($postData['email'], FILTER_VALIDATE_EMAIL)) {
        // L'email n'est pas valide, on stocke un message d'erreur en session
        $_SESSION['LOGIN_ERROR_MESSAGE'] = 'Gars ! Il faut un email valide pour soumettre le formulaire, c\'est la base.';

    } else {
        // ÉTAPE 2 : Recherche de l'utilisateur dans la base de données

        /*
         * $abonnes vient de functions.php et contient tous les utilisateurs
         * On parcourt le tableau pour trouver une correspondance
         */
        foreach ($abonnes as $user) {
            /*
             * Vérification des identifiants
             *
             * ⚠️ ATTENTION : Comparaison du mot de passe en clair (NON SÉCURISÉ)
             * En production, utilisez password_verify() avec des mots de passe hachés
             */
            if (
                $user['mail'] === $postData['email'] &&
                $user['pswd'] === $postData['mdp']
            ) {
                /*
                 * ✅ AUTHENTIFICATION RÉUSSIE
                 *
                 * On stocke les informations de l'utilisateur en session
                 * Attention : On ne stocke JAMAIS le mot de passe en session !
                 */
                $_SESSION['LOGGED_USER'] = [
                    'email' => $user['mail'],
                    'nom' => $user['nom'],
                    'prenom' => $user['prenom'],
                ];
            }
        }


        // ÉTAPE 3 : Vérification du résultat de l'authentification
        if (!isset($_SESSION['LOGGED_USER'])) {
            /*
             * ❌ ÉCHEC DE L'AUTHENTIFICATION
             *
             * Aucun utilisateur ne correspond aux identifiants fournis
             * On crée un message d'erreur
             *
             * sprintf() permet de formater une chaîne avec des variables
             * strip_tags() retire les balises HTML pour éviter les injections XSS
             */
            $_SESSION['LOGIN_ERROR_MESSAGE'] = sprintf(
                'Les informations envoyées ne permettent pas de vous identifier : (%s/%s)',
                $postData['email'],
                strip_tags($postData['mdp'])
            );
        }
    }

    // ============================================================================
    // REDIRECTION VERS LA PAGE D'ACCUEIL
    // ============================================================================

    /*
     * Dans tous les cas (succès ou échec), on redirige vers l'accueil
     * - En cas de succès : l'utilisateur verra le contenu réservé
     * - En cas d'échec : l'utilisateur verra le message d'erreur
     */
    redirectToUrl('index.php');
}
