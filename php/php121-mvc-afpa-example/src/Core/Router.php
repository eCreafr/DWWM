<?php

namespace Afpa\Core;

/**
 * Classe gérant le routage du site.
 */
class Router
{
    // liste des routes de l'application
    protected $routes = [];

    /**
     * Function à appeler pour ajouter une route.
     * 
     * @param $route Le chemin de la route par exemple pour "www.gmail.com/login" la route est "/login"
     * @param $controller Le nom du controller qui gérera les requête vers cette route
     * @param $action Nom de la fonction du controller qui va gérer la requête
     */
    public function addRoute(string $route, string $controller, string $action)
    {
        // modification du tableau association des routes pour ajouter le controller
        $this->routes[$route] = ['controller' => $controller, 'action' => $action];
    }

    /**
     * Prend une URI et redirige la page
     */
    public function dispatch(string $uri)
    {
        // Vérification de l'existence de la route
        if (array_key_exists($uri, $this->routes))
        {
            // si la route existe on récupère le controller associé
            $controller_class_name = $this->routes[$uri]['controller'];
            
            // et l'action associée
            $action = $this->routes[$uri]['action'];

            // instanciation du controller
            $controller = new $controller_class_name();

            // on effectuer l'action demandée
            $controller->$action();
        } else {
            // TODO redirection vers une page 404
            throw new \Exception("No route found for URI: $uri");
        }
    }
}
