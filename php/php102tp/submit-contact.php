<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message reçu</title>
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
        <div class="email-header">
            <h1>Confirmation de votre message</h1>
        </div>
        <div class="email-body">
            <p>Bonjour,</p>
            <p>Nous avons bien reçu votre message. Voici un récapitulatif :</p>
            <p><strong>Adresse email :</strong> <span class="highlight"><?= htmlspecialchars($_POST['email'] ?? 'Non fourni'); ?></span></p>
            <p><strong>Message :</strong></p>
            <blockquote>
                <?= nl2br(htmlspecialchars($_POST['message'] ?? 'Aucun message fourni.')); ?>
            </blockquote>
            <p>Merci de nous avoir contactés. Nous reviendrons vers vous dès que possible.</p>
        </div>
        <div class="email-footer">
            <p>Ceci est une simulation d'email. Aucun message n'a été envoyé réellement.</p>
        </div>
    </div>
</body>

</html>