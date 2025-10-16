<?php
/*
 * Fichier d'en-tête HTML - head.php
 * ==================================
 *
 * Ce fichier contient les éléments qui doivent être chargés dans la balise <head>
 * de chaque page du site. Il initialise également les éléments PHP essentiels.
 *
 * 🎯 Objectif : Centraliser les imports et configurations communs à toutes les pages
 */

// 1. Démarrage de la session PHP
// Permet de stocker des informations côté serveur entre les différentes pages
// (comme les informations de l'utilisateur connecté)
session_start();

// 2. Inclusion du fichier de connexion à la base de données
// __DIR__ donne le chemin absolu du dossier actuel, garantissant que le fichier sera trouvé
require(__DIR__ . '/db.php');

// 3. Inclusion du fichier de fonctions utilitaires
// Ce fichier contient toutes les fonctions réutilisables du projet
require(__DIR__ . '/functions.php');
?>

<!-- 4. Import de Bootstrap pour le style CSS -->
<!-- Bootstrap est un framework CSS qui facilite la création d'interfaces responsive -->
<link href="../../css/bootstrap.min.css" rel="stylesheet">

<!-- 5. Définition de l'encodage de caractères -->
<!-- UTF-8 permet d'afficher correctement les accents et caractères spéciaux -->
<meta charset="utf-8" />