<!--
    Traitement du formulaire de contact - submit-contact.php
    =========================================================

    Ce fichier affiche une simulation d'email de confirmation après la soumission
    du formulaire de contact.

    ⚠️ IMPORTANT : Ce fichier ne fait PAS d'envoi d'email réel, c'est une simulation !
    Il affiche simplement un récapitulatif des données reçues.

    📚 Concepts abordés :
    - Récupération des données POST
    - Sécurité : htmlspecialchars() pour prévenir les injections XSS
    - nl2br() pour convertir les retours à la ligne en <br>
    - Opérateur null coalescing (??) pour gérer les valeurs manquantes
    - CSS intégré pour un affichage type email

    💡 Pour un vrai système de contact, il faudrait :
    - Utiliser mail() ou PHPMailer pour envoyer un vrai email
    - Valider les données côté serveur
    - Implémenter un CAPTCHA pour éviter le spam
    - Logger les messages dans une base de données
-->

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message reçu</title>

    <!-- Styles CSS pour simuler un email -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }

        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .email-header {
            background: #007bff;
            color: #fff;
            padding: 10px 15px;
            border-radius: 8px 8px 0 0;
            text-align: center;
        }

        .email-body {
            padding: 15px;
        }

        .email-footer {
            background: #f4f4f4;
            padding: 10px;
            text-align: center;
            font-size: 12px;
            color: #777;
            border-radius: 0 0 8px 8px;
        }

        .highlight {
            color: #007bff;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <!-- En-tête de l'email -->
        <div class="email-header">
            <h1>Confirmation de votre message</h1>
        </div>

        <!-- Corps de l'email -->
        <div class="email-body">
            <p>Bonjour,</p>
            <p>Nous avons bien reçu votre message. Voici un récapitulatif :</p>

            <!--
                Affichage de l'email avec sécurisation
                ========================================

                - $_POST['email'] : Récupère l'email envoyé via le formulaire
                - ?? 'Non fourni' : Si l'email n'existe pas, affiche "Non fourni" (opérateur null coalescing)
                - htmlspecialchars() : Protège contre les injections XSS en convertissant les caractères spéciaux
            -->
            <p><strong>Adresse email :</strong> <span class="highlight"><?= htmlspecialchars($_POST['email'] ?? 'Non fourni'); ?></span></p>

            <p><strong>Message :</strong></p>
            <blockquote>
                <!--
                    Affichage du message avec formatage
                    ====================================

                    - nl2br() : Convertit les retours à la ligne (\n) en balises <br> HTML
                    - htmlspecialchars() : Sécurise le contenu contre les injections XSS
                    - $_POST['message'] ?? '...' : Valeur par défaut si le message est vide
                -->
                <?= nl2br(htmlspecialchars($_POST['message'] ?? 'Aucun message fourni.')); ?>
            </blockquote>

            <p>Merci de nous avoir contactés. Nous reviendrons vers vous dès que possible.</p>
        </div>

        <!-- Pied de page avec avertissement -->
        <div class="email-footer">
            <p>Ceci est une simulation d'email. Aucun message n'a été envoyé réellement.</p>
        </div>
    </div>
</body>

</html>
