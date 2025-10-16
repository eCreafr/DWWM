<?php
/**
 * ============================================
 * FICHIER DE CONNEXION À LA BASE DE DONNÉES
 * ============================================
 *
 * Ce fichier établit la connexion à la base de données MySQL
 * Il sera inclus dans tous les autres fichiers qui ont besoin d'accéder à la BDD
 *
 * IMPORTANT : Ce fichier utilise PDO (PHP Data Objects) pour se connecter
 * PDO est une extension PHP qui fournit une interface unifiée pour accéder aux BDD
 */

// ============================================
// TENTATIVE DE CONNEXION AVEC GESTION D'ERREURS
// ============================================
try {
    // Création d'une nouvelle connexion PDO à MySQL
    // Syntaxe : new PDO('mysql:host=serveur;dbname=nom_bdd;charset=encodage', 'utilisateur', 'mot_de_passe')

    $mysqlClient = new PDO(
        'mysql:host=localhost;dbname=sport_2000;charset=utf8',  // DSN (Data Source Name) : informations de connexion
        'root',                                                   // Nom d'utilisateur MySQL (ici root = admin)
        ''                                                        // Mot de passe (vide en local, mais devrait être sécurisé en production !)
    );

    // Configuration optionnelle : activer les erreurs PDO sous forme d'exceptions
    // Cela permet de mieux gérer les erreurs avec try/catch
    $mysqlClient->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (Exception $e) {
    // Si la connexion échoue, on capture l'exception et on arrête tout
    // die() affiche un message et stoppe immédiatement l'exécution du script
    die('Erreur de connexion à la base de données : ' . $e->getMessage());
}

/**
 * NOTES PÉDAGOGIQUES :
 *
 * 1. PDO vs mysqli :
 *    - PDO est plus moderne et fonctionne avec plusieurs types de BDD (MySQL, PostgreSQL, etc.)
 *    - mysqli ne fonctionne qu'avec MySQL
 *
 * 2. Pourquoi try/catch ?
 *    - Permet de gérer proprement les erreurs de connexion
 *    - Sans cela, PHP afficherait des erreurs brutes avec des infos sensibles
 *
 * 3. Sécurité en production :
 *    - NE JAMAIS laisser un mot de passe vide !
 *    - Utiliser des variables d'environnement pour stocker les identifiants
 *    - Ne jamais commiter ce fichier avec de vrais identifiants sur Git
 *
 * 4. La variable $mysqlClient :
 *    - Contient l'objet de connexion PDO
 *    - Sera utilisée dans tous les autres fichiers pour exécuter des requêtes SQL
 *    - Son nom peut être différent selon les projets (parfois $pdo, $db, $connection, etc.)
 */
