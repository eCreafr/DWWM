<?php

namespace Afpa\Controllers;

use Afpa\Core\Controller;

/**
 * Exemple d'un contrôleur simple.
 */
class HomeController extends Controller 
{
    /**
     * Le nom de la fonction est à ajouter dans le fichier "Routes.php"
     */
    public function index()
    {
        $name = "Ada";
        // "compact" permet ici de créer un tableau associatif à partir de variables
        // une variable portant le nom "name" a été déclarée à la ligne 17
        // compact va venir la récupérer et créer le tableau suivant :
        // [ "name" => "Ada" ]
        $data = compact("name");

        // la ligne suivante permet de passer le tableau association $data à la vue "Home"
        $this->render('Home', $data);
    }
}