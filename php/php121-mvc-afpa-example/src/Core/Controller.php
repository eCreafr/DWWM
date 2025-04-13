<?php

namespace Afpa\Core;

/**
 * Classe controller mère qui sera à hériter pour mettre en place des controlleurs custom
 */
class Controller
{
    /**
     * Cette fonction include le fichier de vue (pour l'afficher) en lui passant les données fournies dans le tableau $data
     * 
     * @param $view_name Le nom de la vue (comme le nom du fichier php associé)
     * @param $data Les données à passer à la vue
     */
    protected function render(string $view_name, $data = [])
    {
        // création de variables à partir du tableau associatif nommé $data
        // par exemple, si le tableau contient [ 'nom' => 'Lovelace', 'prenom' => 'Ada' ] deux variables $nom et $prenom seront créées
        extract($data);

        ob_start();
        // Intégration du contenu du HOME
        require(__DIR__."/../Views/$view_name.php");
        // préparation de la variable $content pour la vue
        $content = ob_get_clean();
        require(__DIR__."/../Views/Layout/Main.php");
    }

}