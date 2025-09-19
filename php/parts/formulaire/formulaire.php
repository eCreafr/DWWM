<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recaptchaToken = $_POST['recaptcha_token'];
    $secretKey = 'votre clé';

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


    $to = 'votre@email.fr';
    $subject = 'Nouveau message de contact';
    $body = "Vous avez reçu un nouveau message de contact :\n\n".
            "Nom : $name\n".
            "Email : $email\n".
            "Téléphone : $phone\n".
            "methode : $radio\n".
            "Message : $message";

    // En-têtes
    $headers = 'From: votre@email.fr' . "\r\n" .
               'Reply-To: votre@email.fr' . "\r\n" .
               'X-Mailer: PHP/' . phpversion();
  
   if (mail($to, $subject, $body, $headers)) {
        echo 'Email envoyé avec succès !';
    } else {
        echo 'Erreur lors de l\'envoi de l\'email.';
    }  */




    // Utilisation de l'API Mailchimp + Mandrill 

    $mandrillKey = 'votre clé mandrill';
    $url = 'https://mandrillapp.com/api/1.0/messages/send.json';

    $data = [
        'key' => $mandrillKey,
        'message' => [
            'from_email' => 'votre@email.fr',
            'to' => [
                ['email' => 'votre@email.fr', 'type' => 'to']
            ],
            'subject' => 'Nouveau message depuis votresite.fr',
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
