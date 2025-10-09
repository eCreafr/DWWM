<?php
// ==================== TRAITEMENT CÔTÉ SERVEUR DU FORMULAIRE DE CONTACT ==================== //
/*
 * Ce fichier PHP traite les données reçues du formulaire :
 * 1. Vérifie que la requête est bien de type POST
 * 2. Valide le token reCAPTCHA avec l'API Google
 * 3. Envoie l'email via l'API Mandrill (Mailchimp)
 *
 * SÉCURITÉ : Toujours valider côté serveur, jamais uniquement côté client !
 */

// Vérifie que la requête HTTP utilisée est POST (et non GET ou autre)
// $_SERVER : variable superglobale contenant les informations du serveur
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ==================== VÉRIFICATION RECAPTCHA V3 ==================== //
    /*
     * reCAPTCHA v3 génère un score entre 0.0 (bot) et 1.0 (humain)
     * Le token généré côté client doit être vérifié côté serveur
     */

    // Récupère le token envoyé par le formulaire
    // $_POST : variable superglobale contenant les données POST
    $recaptchaToken = $_POST['recaptcha_token'];

    // Clé secrète reCAPTCHA (à garder confidentielle côté serveur)
    // IMPORTANT : Remplacer par votre vraie clé secrète
    $secretKey = 'votre-clé';

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

    // ==================== RÉCUPÉRATION DES DONNÉES DU FORMULAIRE ==================== //
    // Les données ont été validées par reCAPTCHA, on peut maintenant les récupérer
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $message = $_POST['message'];

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
    // Destinataire de l'email
    $to = 'votre@email.fr';

    // Sujet de l'email
    $subject = 'Nouveau message de contact';

    // Corps du message (contenu de l'email)
    // Opérateur de concaténation : . (point)
    // \n : retour à la ligne
    $body = "Vous avez reçu un nouveau message de contact :\n\n" .
        "Nom : $name\n" .
        "Email : $email\n" .
        "Téléphone : $phone\n" .
        "Message : $message";

    // En-têtes HTTP de l'email
    // From : adresse d'expéditeur
    // Reply-To : adresse de réponse
    // X-Mailer : identifie le logiciel utilisé pour envoyer l'email
    // \r\n : retour chariot + nouvelle ligne (standard email)
    $headers = 'From: votre@email.fr' . "\r\n" .
        'Reply-To: votre@email.fr' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    // Tentative d'envoi de l'email
    // La fonction mail() retourne true en cas de succès, false en cas d'échec
    if (mail($to, $subject, $body, $headers)) {
        echo 'Email envoyé avec succès !';
    } else {
        echo 'Erreur lors de l\'envoi de l\'email.';
    }

*/ // Fin de la section mail()

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
     * 2. Activer Mandrill (service payant)
     * 3. Générer une clé API
     */

    // Clé API Mandrill (à garder secrète)
    // IMPORTANT : Remplacer par votre vraie clé API
    $mandrillKey = 'votre-clé-mandrill';

    // URL de l'endpoint API Mandrill pour l'envoi de messages
    $url = 'https://mandrillapp.com/api/1.0/messages/send.json';

    // ==================== CONSTRUCTION DU PAYLOAD JSON ==================== //
    // Tableau associatif PHP qui sera converti en JSON
    $data = [
        'key' => $mandrillKey, // Clé d'authentification

        // Structure du message
        'message' => [
            'from_email' => 'votre@email.fr', // Expéditeur (doit être vérifié dans Mandrill)

            // Tableau des destinataires
            'to' => [
                ['email' => 'votre@email.fr', 'type' => 'to']
            ],

            // Sujet de l'email
            'subject' => 'Nouveau message depuis votresite.fr',

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
    $context  = stream_context_create($options);

    // ==================== ENVOI DE LA REQUÊTE API ==================== //
    // file_get_contents() : peut aussi faire des requêtes POST avec un contexte
    // $url : endpoint de l'API
    // false : pas de include_path
    // $context : contexte avec les options HTTP
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
