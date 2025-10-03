<?php
session_start();
require(__DIR__ . '/db.php');
include(__DIR__ . '/functions.php');


$postData = $_POST;

// Validation du formulaire

if (isset($postData['email']) && isset($postData['mdp'])) {
    if (!filter_var($postData['email'], FILTER_VALIDATE_EMAIL)) {
        $_SESSION['LOGIN_ERROR_MESSAGE'] = 'Gars ! Il faut un email valide pour soumettre le formulaire, c\'est la base.';
    } else {
        foreach ($abonnes as $user) {
            if (
                $user['mail'] === $postData['email'] &&
                $user['pswd'] === $postData['mdp']
            ) {
                $_SESSION['LOGGED_USER'] = [
                    'email' => $user['mail'],
                    'nom' => $user['nom'],
                    'prenom' => $user['prenom'],
                ];
            }
        }


        if (!isset($_SESSION['LOGGED_USER'])) {
            $_SESSION['LOGIN_ERROR_MESSAGE'] = sprintf(
                'Les informations envoyées ne permettent pas de vous identifier : (%s/%s)',
                $postData['email'],
                strip_tags($postData['mdp'])
            );
        }
    }

    redirectToUrl('index.php');
}
