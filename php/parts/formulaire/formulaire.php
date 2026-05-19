<?php
// ==================== TRAITEMENT CÔTÉ SERVEUR DU FORMULAIRE DE CONTACT ==================== //
/*
 * Ce fichier PHP traite les données reçues du formulaire :
 * 1. Vérifie que la requête est bien de type POST
 * 2. Valide le token reCAPTCHA avec l'API Google
 * 3. Sanitize les données reçues (sécurité)
 * 4. Envoie l'email via l'API Mandrill (Mailchimp)
 *
 * SÉCURITÉ : Toujours valider côté serveur, jamais uniquement côté client !
 */

// Charge les variables d'environnement depuis le fichier .env
// Les clés API sont ainsi hors du code source versionné sur Git
require_once 'config.php';

// Vérifie que la requête HTTP utilisée est POST (et non GET ou autre)
// $_SERVER : variable superglobale contenant les informations du serveur
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ==================== VÉRIFICATION RECAPTCHA V3 ==================== //
    /*
     * reCAPTCHA v3 génère un score entre 0.0 (bot) et 1.0 (humain)
     * Le token généré côté client doit être vérifié côté serveur
     */

    // Récupère le token envoyé par le formulaire
    // ?? '' : opérateur null-coalescent — valeur par défaut si la clé n'existe pas
    $recaptchaToken = $_POST['recaptcha_token'] ?? '';

    // Clé secrète reCAPTCHA chargée depuis le fichier .env
    $secretKey = $_ENV['RECAPTCHA_SECRET_KEY'];

    // Appel à l'API Google pour vérifier le token
    // file_get_contents() : fait une requête HTTP GET
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$recaptchaToken");

    // Convertit la réponse JSON en tableau PHP
    // true : retourne un tableau associatif plutôt qu'un objet
    $responseKeys = json_decode($response, true);

    // Vérifie le succès de la vérification ET que le score est suffisant (> 0.5)
    // Score < 0.5 = probable bot
    if (!$responseKeys["success"] || $responseKeys["score"] < 0.5) {
        echo 'Échec de la vérification reCAPTCHA. Essayez à nouveau.';
        exit; // Arrête l'exécution du script
    }

    // ==================== RÉCUPÉRATION ET SANITIZATION DES DONNÉES ==================== //
    /*
     * SANITIZATION : nettoyer les données reçues avant de les utiliser
     * - strip_tags()       : supprime toutes les balises HTML/PHP
     * - trim()             : supprime les espaces en début et fin de chaîne
     * - htmlspecialchars() : convertit les caractères spéciaux (<, >, &, "...) en entités HTML
     * - filter_var()       : valide et nettoie selon un filtre prédéfini PHP
     * - preg_replace()     : supprime les caractères non autorisés (ex : téléphone)
     *
     * Sans sanitization, un utilisateur malveillant pourrait injecter du code
     * dans les emails ou tenter une attaque XSS / injection de headers.
     */
    $name    = htmlspecialchars(strip_tags(trim($_POST['name']    ?? '')));
    $email   = filter_var(trim($_POST['email']   ?? ''), FILTER_SANITIZE_EMAIL);
    $phone   = preg_replace('/[^0-9+\s\-\(\)]/', '', trim($_POST['phone'] ?? ''));
    $message = htmlspecialchars(strip_tags(trim($_POST['message'] ?? '')));

    // Validation supplémentaire : vérifie que l'email est bien formé
    // FILTER_VALIDATE_EMAIL : retourne false si le format email est invalide
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo 'Adresse email invalide.';
        exit;
    }

    // Adresse email chargée depuis le fichier .env
    $contactEmail = $_ENV['CONTACT_EMAIL'];

    // ==================== MÉTHODE 1 : ENVOI EMAIL AVEC LA FONCTION mail() DE PHP (DÉSACTIVÉE) ==================== //
    /*
     * ATTENTION : Cette méthode est commentée car peu fiable
     * - Nécessite un serveur SMTP configuré
     * - Souvent bloquée par les hébergeurs (spam)
     * - Les emails finissent souvent en spam
     *
     * FONCTION mail() :
     * mail($destinataire, $sujet, $corps, $en-têtes)
     * Fonctionne uniquement si le serveur PHP est configuré pour envoyer des emails
     */

    /*
    $to      = $contactEmail;
    $subject = 'Nouveau message de contact';

    // Corps du message (contenu de l'email)
    // Opérateur de concaténation : . (point) — \n : retour à la ligne
    $body = "Vous avez reçu un nouveau message de contact :\n\n" .
        "Nom : $name\n" .
        "Email : $email\n" .
        "Téléphone : $phone\n" .
        "Message : $message";

    // En-têtes HTTP de l'email
    // From : adresse d'expéditeur — Reply-To : adresse de réponse
    // \r\n : retour chariot + nouvelle ligne (standard email)
    $headers = 'From: ' . $contactEmail . "\r\n" .
        'Reply-To: ' . $contactEmail . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    // La fonction mail() retourne true en cas de succès, false en cas d'échec
    if (mail($to, $subject, $body, $headers)) {
        echo 'Email envoyé avec succes !';
    } else {
        echo 'Erreur lors de l\'envoi de l\'email.';
    }
    */

    // ==================== MÉTHODE 2 : ENVOI EMAIL VIA API MANDRILL (ACTIVE) ==================== //
    /*
     * MANDRILL : Service d'envoi d'emails transactionnels par Mailchimp
     * AVANTAGES :
     * - Fiable et professionnel
     * - Évite les filtres anti-spam
     * - Statistiques et suivi des envois
     * - API RESTful simple à utiliser
     *
     * PRÉREQUIS :
     * 1. Créer un compte Mailchimp
     * 2. Activer Mandrill (offre gratuite disponible)
     * 3. Générer une clé API → à renseigner dans le fichier .env
     * 4. Vérifier l'adresse email d'expédition dans Mandrill
     */

    // Clé API Mandrill chargée depuis le fichier .env
    $mandrillKey = $_ENV['MANDRILL_API_KEY'];

    // URL de l'endpoint API Mandrill pour l'envoi de messages
    $url = 'https://mandrillapp.com/api/1.0/messages/send.json';

    // ==================== CONSTRUCTION DU PAYLOAD JSON ==================== //
    // Tableau associatif PHP qui sera converti en JSON
    $data = [
        'key' => $mandrillKey, // Clé d'authentification

        // Structure du message
        'message' => [
            'from_email' => $contactEmail, // Expéditeur (doit être vérifié dans Mandrill)

            // Tableau des destinataires
            'to' => [
                ['email' => $contactEmail, 'type' => 'to']
            ],

            // Sujet de l'email
            'subject' => 'Nouveau message depuis votre site',

            // Corps du message en texte brut
            // \n : retour à la ligne
            'text' => "Nom : $name\nEmail : $email\nTéléphone : $phone\nMessage : $message\n"
        ]
    ];

    // ==================== CONFIGURATION DE LA REQUÊTE HTTP ==================== //
    /*
     * stream_context_create() : crée un contexte pour les flux
     * Permet de configurer des options pour les requêtes HTTP
     */
    $options = [
        'http' => [
            // En-tête : indique que le contenu est du JSON
            'header'  => "Content-Type: application/json\r\n",

            // Méthode HTTP POST
            'method'  => 'POST',

            // Corps de la requête : convertit le tableau PHP en JSON
            // json_encode() : convertit un tableau PHP en chaîne JSON
            'content' => json_encode($data),
        ],
    ];

    // Crée un contexte de flux avec les options définies
    $context = stream_context_create($options);

    // ==================== ENVOI DE LA REQUÊTE API ==================== //
    // file_get_contents() : peut aussi faire des requêtes POST avec un contexte
    // $url : endpoint de l'API — false : pas de include_path — $context : options HTTP
    $result = file_get_contents($url, false, $context);

    // ==================== GESTION DE LA RÉPONSE ==================== //
    // Vérifie si la requête a échoué (FALSE en cas d'erreur réseau)
    if ($result === FALSE) {
        echo 'Erreur lors de l\'envoi de l\'email.';
    } else {
        // Succès : message renvoyé à script.js via fetch()
        // Ce message est détecté par data.includes('succes') en JavaScript
        echo 'Email envoyé avec succes !';
    }
    // Fin de la section Mandrill

} // Fin du if ($_SERVER['REQUEST_METHOD'] === 'POST')
