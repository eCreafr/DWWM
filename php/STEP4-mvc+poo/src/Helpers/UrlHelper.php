<?php

namespace App\Helpers;

/**
 * Classe UrlHelper - Utilitaires pour la gestion des URLs
 *
 * Cette classe contient des méthodes pour générer des URLs propres et SEO-friendly
 */
class UrlHelper
{
    /**
     * Génère une URL SEO-friendly pour un article
     *
     * Format généré :
     * - Avec score : articles/123-titre-de-l-article-3-2.html
     * - Sans score : articles/123-titre-de-l-article.html
     *
     * @param int $id L'identifiant de l'article
     * @param string $titre Le titre de l'article
     * @param string|null $score Le score du match (optionnel)
     * @return string L'URL générée
     */
    public static function createArticleUrl(int $id, string $titre, ?string $score = null): string
    {
        // Transforme le titre en slug (format URL-friendly)
        $slug = StringHelper::slugify($titre);

        // Si un score est fourni, on l'ajoute dans l'URL
        if ($score !== null && $score !== '') {
            // Nettoie le score pour l'URL (remplace espaces et caractères spéciaux par tirets)
            $scoreSlug = StringHelper::slugify($score);
            return "articles/{$id}-{$slug}-{$scoreSlug}.html";
        }

        // URL sans score
        return "articles/{$id}-{$slug}.html";
    }

    /**
     * Génère une URL complète avec BASE_URL
     *
     * @param string $path Le chemin relatif (ex: "home.html")
     * @return string L'URL complète
     */
    public static function url(string $path): string
    {
        return BASE_URL . '/' . ltrim($path, '/');
    }

    /**
     * Redirige vers une URL
     *
     * @param string $path Le chemin où rediriger
     * @param int $statusCode Le code HTTP de redirection (301 permanent, 302 temporaire)
     * @return void
     */
    public static function redirect(string $path, int $statusCode = 302): void
    {
        // Génère l'URL complète
        $url = self::url($path);

        // Envoie l'en-tête de redirection HTTP
        header("Location: {$url}", true, $statusCode);

        // Arrête l'exécution du script
        exit;
    }

    /**
     * Retourne l'URL actuelle de la page
     *
     * @return string L'URL complète de la page actuelle
     */
    public static function getCurrentUrl(): string
    {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
        return $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }
}
