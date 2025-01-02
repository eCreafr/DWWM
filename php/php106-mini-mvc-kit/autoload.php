<?php

class Autoloader
{
    public static function register()
    {
        spl_autoload_register(function ($class) {
            $file = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
            if (file_exists($file)) {
                require $file;
                return true;
            }
            return false;
        });
    }
}

Autoloader::register();

/* Cette classe sert à automatiser le chargement des classes PHP dans votre projet. Voici comment elle fonctionne :

La fonction register() est une méthode statique qui enregistre une fonction de chargement automatique via spl_autoload_register()
Quand PHP a besoin d'utiliser une classe qui n'a pas encore été chargée, il appelle automatiquement cette fonction avec le nom complet de la classe (namespace inclus) en paramètre
La fonction fait alors :

Remplace les antislashs () du namespace par le séparateur de dossiers de votre système (/ pour Linux, \ pour Windows) grâce à DIRECTORY_SEPARATOR
Ajoute '.php' à la fin pour obtenir le chemin complet du fichier
Vérifie si le fichier existe avec file_exists()
Si oui, charge le fichier avec require



Par exemple, si vous utilisez une classe App\Controllers\UserController, l'autoloader va chercher le fichier :

Sur Windows : App\Controllers\UserController.php
Sur Linux : App/Controllers/UserController.php

L'avantage principal est que vous n'avez plus besoin d'inclure manuellement tous vos fichiers de classes avec require/include. Le chargement se fait automatiquement quand PHP en a besoin, ce qui est particulièrement utile dans une architecture MVC où vous avez beaucoup de classes réparties dans différents dossiers. */