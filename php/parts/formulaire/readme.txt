================================================================================
                    PROJET FORMULAIRE DE CONTACT SÉCURISÉ
================================================================================

Ce projet est un formulaire de contact complet avec protection anti-spam
reCAPTCHA v3 et envoi d'emails via l'API Mandrill (Mailchimp).

================================================================================
                              ARCHITECTURE DU PROJET
================================================================================

┌─────────────────────────────────────────────────────────────────────────────┐
│  1. INDEX.HTML - Interface utilisateur (Frontend)                          │
└─────────────────────────────────────────────────────────────────────────────┘

    Structure :
    -----------
    • Page HTML responsive avec navigation Bootstrap
    • Panneau latéral (Offcanvas) contenant le formulaire
    • Design mobile-first avec menu hamburger

    Éléments clés :
    ---------------
    • En-tête avec meta tags et imports CSS/JS (Bootstrap, Google Fonts, etc.)
    • Navigation responsive avec fermeture automatique sur mobile
    • Formulaire de contact avec validation HTML5 (required, type="email", etc.)
    • Champ caché pour le token reCAPTCHA (généré dynamiquement)
    • Scripts inline pour la gestion du comportement du menu

    Technologies utilisées :
    ------------------------
    • Bootstrap 5.3.3 (composants UI)
    • Google Fonts (typographie)
    • Bootstrap Icons & Font Awesome (icônes)
    • Google reCAPTCHA v3 (anti-spam)


┌─────────────────────────────────────────────────────────────────────────────┐
│  2. SCRIPT.JS - Logique client (JavaScript)                                │
└─────────────────────────────────────────────────────────────────────────────┘

    Fonctionnalités principales :
    -----------------------------
    ✓ Validation des champs côté client avant envoi
    ✓ Génération du token reCAPTCHA v3 invisible (sans challenge visuel)
    ✓ Envoi AJAX avec l'API Fetch moderne (sans rechargement de page)
    ✓ Gestion des réponses de succès et d'erreur
    ✓ Affichage dynamique des messages à l'utilisateur
    ✓ Réinitialisation automatique du formulaire après succès

    Concepts pédagogiques expliqués :
    ----------------------------------
    • event.preventDefault() - Empêcher le comportement par défaut
    • .trim() - Nettoyage des espaces
    • encodeURIComponent() - Encodage sécurisé des données URL
    • Promesses JavaScript (.then() et .catch())
    • API Fetch pour les requêtes HTTP asynchrones
    • Manipulation du DOM (textContent, style.color, reset())


┌─────────────────────────────────────────────────────────────────────────────┐
│  3. FORMULAIRE.PHP - Traitement serveur (Backend PHP)                      │
└─────────────────────────────────────────────────────────────────────────────┘

    Fonctionnalités principales :
    -----------------------------
    ✓ Vérification de la méthode HTTP (POST uniquement)
    ✓ Validation du token reCAPTCHA côté serveur (SÉCURITÉ ESSENTIELLE)
    ✓ Analyse du score anti-bot (0.0 = bot, 1.0 = humain)
    ✓ Rejet si score < 0.5
    ✓ Récupération sécurisée des données POST
    ✓ Envoi d'email via API Mandrill

    Deux méthodes d'envoi documentées :
    ------------------------------------
    1. Fonction mail() de PHP (DÉSACTIVÉE)
       ↳ Peu fiable, souvent en spam, nécessite config SMTP
       ↳ Commentée à des fins pédagogiques

    2. API Mandrill de Mailchimp (ACTIVE)
       ↳ Service professionnel d'envoi transactionnel
       ↳ Fiable, avec statistiques et délivrabilité optimale
       ↳ Requête HTTP POST avec payload JSON

    Concepts pédagogiques expliqués :
    ----------------------------------
    • Variables superglobales PHP ($_SERVER, $_POST)
    • Appels d'API externes avec file_get_contents()
    • json_decode() et json_encode()
    • stream_context_create() pour les requêtes HTTP
    • Gestion des erreurs et validation des réponses


================================================================================
                        🎯 POINTS PÉDAGOGIQUES COUVERTS
================================================================================

    📚 Architecture web moderne :
    -----------------------------
    • Séparation Frontend / Backend
    • Communication client-serveur via API REST
    • Architecture MVC simplifiée

    🔒 Sécurité web :
    -----------------
    • Protection anti-spam avec reCAPTCHA v3
    • Validation côté client ET serveur (double validation)
    • Encodage sécurisé des données (encodeURIComponent)
    • Variables superglobales PHP sécurisées

    💻 Technologies JavaScript :
    ----------------------------
    • Manipulation du DOM
    • Événements (addEventListener, preventDefault)
    • AJAX moderne avec Fetch API
    • Promesses JavaScript (async/await)
    • Gestion d'erreurs réseau

    🐘 Technologies PHP :
    ---------------------
    • Traitement de formulaires POST
    • Appels d'API externes
    • Gestion JSON
    • Contextes de flux HTTP
    • Variables superglobales

    🌐 APIs externes :
    ------------------
    • Google reCAPTCHA v3 (détection de bots)
    • Mandrill API (envoi d'emails transactionnels)
    • Requêtes HTTP POST avec authentification


================================================================================
                            ⚙️ CONFIGURATION REQUISE
================================================================================

    Pour utiliser ce projet en production :
    ---------------------------------------
    1. Obtenir une clé reCAPTCHA v3 sur https://www.google.com/recaptcha/admin
       • Clé de site (publique) → à mettre dans index.html et script.js
       • Clé secrète (privée) → à mettre dans formulaire.php

    2. Créer un compte Mandrill via Mailchimp
       • Activer Mandrill (service payant)
       • Générer une clé API → à mettre dans formulaire.php
       • Vérifier l'adresse email d'expédition

    3. Remplacer les placeholders suivants :
       • 'votre-clef' → Clé de site reCAPTCHA
       • 'votre clé' → Clé secrète reCAPTCHA
       • 'votre clé mandrill' → Clé API Mandrill
       • 'votre@email.fr' → Votre adresse email
       • 'https://votresite.fr' → URL de votre site


================================================================================
                                📖 RESSOURCES
================================================================================

    Documentation officielle :
    --------------------------
    • Bootstrap : https://getbootstrap.com/docs/5.3/
    • reCAPTCHA : https://developers.google.com/recaptcha
    • Mandrill : https://mandrillapp.com/api/docs/
    • Fetch API : https://developer.mozilla.org/fr/docs/Web/API/Fetch_API


================================================================================
                            © 2024 - Projet pédagogique
================================================================================