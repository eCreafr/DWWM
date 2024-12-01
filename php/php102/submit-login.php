<?php
session_start();
require_once(__DIR__ . '/variables.php');
require_once(__DIR__ . '/functions.php');


$postData = $_POST;

// Validation du formulaire
if (isset($postData['email']) && isset($postData['mdp'])) {
    if (!filter_var($postData['email'], FILTER_VALIDATE_EMAIL)) {
        $_SESSION['LOGIN_ERROR_MESSAGE'] = 'Gars ! Il faut un email valide pour soumettre le formulaire, c\'est la base.';
    } else {
        foreach ($abonnes as $user) {
            if (
                $user['email'] === $postData['email'] &&
                $user['mdp'] === $postData['mdp']
            ) {
                $_SESSION['LOGGED_USER'] = [
                    'email' => $user['email'],
                    'nom' => $user['nom'],
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

    redirectToUrl('../php102-index.php');
}
