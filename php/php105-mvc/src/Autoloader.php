<?php

namespace App;

/**
 * Classe Autoloader - Chargement automatique des classes
 *
 * L'autoloader permet de charger automatiquement les fichiers de classe
 * sans avoir à utiliser require/include manuellement partout.
 *
 * Il suit la norme PSR-4 qui définit un standard de chargement automatique :
 * - Le namespace correspond à la structure de dossiers
 * - Exemple : App\Models\Article => src/Models/Article.php
 *
 * Avantages :
 * - Plus besoin de require_once pour chaque classe
 * - Les classes sont chargées uniquement quand on en a besoin (lazy loading)
 * - Code plus propre et maintenable
 */
class Autoloader
{
    /**
     * Enregistre l'autoloader auprès de PHP
     *
     * Cette méthode doit être appelée une seule fois au début de l'application
     * Elle dit à PHP : "Quand tu rencontres une classe que tu ne connais pas,
     * appelle ma méthode load() pour la charger"
     */
    public static function register(): void
    {
        // spl_autoload_register enregistre une fonction de chargement automatique
        // Ici, on enregistre la méthode load() de cette classe
        spl_autoload_register([__CLASS__, 'load']);
    }

    /**
     * Charge automatiquement une classe
     *
     * Cette méthode est appelée automatiquement par PHP chaque fois
     * qu'on utilise une classe qui n'a pas encore été chargée.
     *
     * @param string $className Le nom complet de la classe avec son namespace
     *                          Exemple : "App\Models\Article"
     */
    public static function load(string $className): void
    {
        // Étape 1 : Vérifier que la classe appartient bien à notre namespace "App"
        // On ne veut charger que nos propres classes, pas celles de bibliothèques externes
        if (strpos($className, 'App\\') !== 0) {
            // Si la classe ne commence pas par "App\", on ne fait rien
            return;
        }

        // Étape 2 : Transformer le namespace en chemin de fichier
        // "App\Models\Article" devient "Models/Article"
        $classPath = str_replace('App\\', '', $className);

        // Étape 3 : Remplacer les backslashes du namespace par des slashes de chemin
        // "Models\Article" devient "Models/Article"
        $classPath = str_replace('\\', '/', $classPath);

        // Étape 4 : Construire le chemin complet vers le fichier
        // __DIR__ = le dossier src (où se trouve Autoloader.php)
        // Résultat : "/chemin/vers/projet/src/Models/Article.php"
        $file = __DIR__ . '/' . $classPath . '.php';

        // Étape 5 : Charger le fichier s'il existe
        if (file_exists($file)) {
            require_once $file;
        }
    }
}
