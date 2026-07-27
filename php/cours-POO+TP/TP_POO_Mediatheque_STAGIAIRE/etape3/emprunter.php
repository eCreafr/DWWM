<?php
declare(strict_types=1);

/**
 * TP POO — ÉTAPE 3 — NIVEAU 2 — À COMPLÉTER.
 *
 * Rôle : traiter un emprunt ou un retour, puis rediriger vers index.php.
 *
 * URL appelée : emprunter.php?id=5&action=emprunter
 *
 * CE FICHIER EST LE PLUS SENSIBLE DU TP :
 * il reçoit des données de l'utilisateur via l'URL. C'est exactement
 * le point qu'un jury inspecte en premier.
 */

require_once __DIR__ . '/src/DocumentRepository.php';

// =====================================================================
// TODO 1 — VALIDER L'ENTRÉE $_GET['id']
// =====================================================================
//
// Ne faites JAMAIS confiance à une donnée reçue par l'URL.
// Utilisez filter_input() avec FILTER_VALIDATE_INT :
//
//   $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
//
// Puis testez : si $id est false ou null (ou <= 0), l'entrée est
// invalide → redirigez vers index.php et arrêtez le script avec exit;
//
// Testez ensuite ces URL, elles doivent TOUTES être rejetées proprement :
//   ?id=abc
//   ?id=-1
//   ?id=1 OR 1=1
//   ?id=<script>alert(1)</script>

// TODO 1 : votre code ici


// =====================================================================
// TODO 2 — VALIDER L'ACTION
// =====================================================================
//
// $_GET['action'] doit valoir 'emprunter' OU 'rendre', rien d'autre.
// Technique attendue : LISTE BLANCHE (whitelist).
//
//   $action = $_GET['action'] ?? '';
//   if (!in_array($action, ['emprunter', 'rendre'], true)) { ... }
//
// Le troisième argument `true` de in_array() active la comparaison
// stricte. Sans lui, in_array(0, ['emprunter']) renvoie true sur
// certaines versions : piège classique.

// TODO 2 : votre code ici


// =====================================================================
// TODO 3 — VÉRIFIER QUE LE DOCUMENT EXISTE
// =====================================================================
//
// Instanciez le Repository, appelez findById($id).
// Si le résultat est null → l'id n'existe pas en base.
// Redirigez vers index.php avec exit; (ne laissez pas le script continuer).

// TODO 3 : votre code ici


// =====================================================================
// TODO 4 — EXÉCUTER L'ACTION, EN GÉRANT LES ERREURS
// =====================================================================
//
// Enveloppez l'appel au Repository dans un try/catch (PDOException $e).
//
//   - Dans le try : appelez setDisponibilite() avec la bonne valeur
//     booléenne selon $action.
//   - Dans le catch : error_log($e->getMessage()) pour la trace, PUIS
//     un message générique à l'utilisateur.
//
// [RÈGLE] N'affichez JAMAIS $e->getMessage() à l'écran. Ce message
// révèle noms de tables, de colonnes et parfois le chemin serveur —
// c'est un cadeau offert à un attaquant.

// TODO 4 : votre code ici


// =====================================================================
// TODO 5 — REDIRIGER VERS index.php
// =====================================================================
//
//   header('Location: index.php');
//   exit;
//
// Pourquoi rediriger plutôt qu'afficher directement ?
// Pour éviter qu'un rafraîchissement de page (F5) ne rejoue l'action.
// Ce motif s'appelle POST/Redirect/GET — sachez le nommer devant le jury.

// TODO 5 : votre code ici
