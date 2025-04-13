<?php

namespace Afpa\Controllers;

use Afpa\Core\Controller;
use Afpa\Models\UtilisateurDAO;

/**
 * Contrôleur permettant de gérer les pages liées aux utilisateurs
 */
class UtilisateurController extends Controller {

    /**
     * Méthode appelée pour charger les utilisateur et les renvoyer à la vue "UtilisateursList"
     */
    public function list()
    {
        // appel à un DAO pour récupérer les utilisateurs
        $utilisateurs = UtilisateurDAO::getAll();
        
        $data = compact("utilisateurs");

        $this->render("UtilisateursList", $data);
    }
}