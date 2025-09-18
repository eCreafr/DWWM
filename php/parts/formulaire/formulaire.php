<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recaptchaToken = $_POST['recaptcha_token'];
    $secretKey = '6LdTiV0qAAAAAGS3MBPXyld68aM5l4dyXo3OvpLo';

    // Vérifier le token reCAPTCHA avec Google
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$recaptchaToken");
    $responseKeys = json_decode($response, true);

    if (!$responseKeys["success"] || $responseKeys["score"] < 0.5) {
        echo 'Échec de la vérification reCAPTCHA. Essayez à nouveau.';
        exit;
    }

    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $message = $_POST['message'];

    /*     // Envoi de l'email php d'ovh


    $to = 'raphael@ecrea.fr';
    $subject = 'Nouveau message de contact';
    $body = "Vous avez reçu un nouveau message de contact :\n\n".
            "Nom : $name\n".
            "Email : $email\n".
            "Téléphone : $phone\n".
            "methode : $radio\n".
            "Message : $message";

    // En-têtes
    $headers = 'From: raphael@ecrea.fr' . "\r\n" .
               'Reply-To: raphael@ecrea.fr' . "\r\n" .
               'X-Mailer: PHP/' . phpversion();
  
   if (mail($to, $subject, $body, $headers)) {
        echo 'Email envoyé avec succès !';
    } else {
        echo 'Erreur lors de l\'envoi de l\'email.';
    }  */




    // Utilisation de l'API Mailchimp + Mandrill 

    $mandrillKey = 'md-Qi_lFbvItMTq1LlbThse5g';
    $url = 'https://mandrillapp.com/api/1.0/messages/send.json';

    $data = [
        'key' => $mandrillKey,
        'message' => [
            'from_email' => 'contact@manongoursaud.fr',
            'to' => [
                ['email' => 'contact@manongoursaud.fr', 'type' => 'to']
            ],
            'subject' => 'Nouveau message depuis manongoursaud.fr',
            'text' => "Nom : $name\nEmail : $email\nTéléphone : $phone\nMessage : $message\n"
        ]
    ];

    $options = [
        'http' => [
            'header'  => "Content-Type: application/json\r\n",
            'method'  => 'POST',
            'content' => json_encode($data),
        ],
    ];

    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    if ($result === FALSE) {
        echo 'Erreur lors de l\'envoi de l\'email.';
    } else {
        echo 'Email envoyé avec succes !';
    }
    // fin de mandrill


}
