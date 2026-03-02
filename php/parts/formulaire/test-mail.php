<?php
// ------------------------------------------------------
// Test simple d'envoi d'e-mail sur un hébergement OVH
// sans bibliothèque externe (pas de PHPMailer) pour verifier si X-mailer est activé
// et si la fonction mail() est opérationnelle.
// ------------------------------------------------------

// Adresse du destinataire (remplacer par la vôtre)
$destinataire = "votre-adresse@exemple.com";

// Sujet du message
$sujet = "✅ Test d'envoi de mail depuis OVH";

// Corps du message (texte brut ou HTML simple)
$message = "
<html>
  <head><title>Test de mail PHP</title></head>
  <body>
    <h3>Bonjour !</h3>
    <p>Ceci est un test d'envoi de mail depuis un hébergement mutualisé OVH 🎉</p>
  </body>
</html>
";

// ------------------------------------------------------
// En-têtes du message
// ------------------------------------------------------
$headers  = "From: \"Test OVH\" <no-reply@" . $_SERVER['SERVER_NAME'] . ">\r\n";
$headers .= "Reply-To: no-reply@" . $_SERVER['SERVER_NAME'] . "\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
$headers .= "X-Mailer: PHP/" . phpversion() . "\r\n"; // ✅ optionnel mais utile pour le test

// ------------------------------------------------------
// Envoi du mail
// ------------------------------------------------------
if (mail($destinataire, $sujet, $message, $headers)) {
    echo "<p style='color:green;'>✅ Mail envoyé avec succès à <strong>$destinataire</strong></p>";
} else {
    echo "<p style='color:red;'>❌ Erreur : le mail n'a pas pu être envoyé.</p>";
}
