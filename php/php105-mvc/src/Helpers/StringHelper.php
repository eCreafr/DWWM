<?php

namespace App\Helpers;

/**
 * Classe StringHelper - Utilitaires pour la manipulation de chaînes de caractères
 *
 * Cette classe contient des méthodes statiques pour manipuler des chaînes.
 * Les méthodes statiques permettent de les appeler sans instancier la classe.
 *
 * Exemple d'utilisation : StringHelper::slugify('Mon Titre');
 */
class StringHelper
{
    /**
     * Transforme une chaîne en slug (format URL-friendly)
     *
     * Un slug est une version "nettoyée" d'un texte pour l'utiliser dans une URL :
     * - Pas d'espaces (remplacés par des tirets)
     * - Pas d'accents (remplacés par leurs équivalents sans accent)
     * - Tout en minuscules
     * - Uniquement des caractères alphanumériques et tirets
     *
     * Exemple : "L'Équipe de France" devient "l-equipe-de-france"
     *
     * @param string $text Le texte à transformer en slug
     * @return string Le slug généré
     */
    public static function slugify(string $text): string
    {
        // Remplace tous les caractères non alphanumériques par un tiret
        // \pL = lettres Unicode, \d = chiffres
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // Translitération : convertit les caractères accentués en ASCII
        // "é" devient "e", "ç" devient "c", etc.
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // Supprime tous les caractères qui ne sont ni lettres, ni chiffres, ni tirets
        $text = preg_replace('~[^-\w]+~', '', $text);

        // Supprime les tirets en début et fin de chaîne
        $text = trim($text, '-');

        // Convertit tout en minuscules
        $text = strtolower($text);

        // Si le résultat est vide, retourne "n-a" (not available)
        return empty($text) ? 'n-a' : $text;
    }

    /**
     * Tronque une chaîne de caractères à une longueur donnée
     *
     * Utile pour afficher un résumé d'un texte long (extrait d'article par exemple)
     *
     * @param string $string La chaîne à tronquer
     * @param int $length La longueur maximale souhaitée
     * @param string $suffix Le suffixe à ajouter si tronqué (par défaut "...")
     * @return string La chaîne tronquée avec le suffixe, ou la chaîne complète si pas besoin
     */
    public static function truncate(string $string, int $length = 20, string $suffix = '...'): string
    {
        // mb_strlen compte correctement les caractères UTF-8 (accents, émojis, etc.)
        if (mb_strlen($string) > $length) {
            // mb_substr coupe la chaîne en respectant l'encodage UTF-8
            return mb_substr($string, 0, $length) . $suffix;
        }

        return $string;
    }

    /**
     * Échappe et nettoie une chaîne pour l'affichage HTML
     *
     * Cette méthode sécurise les données utilisateur avant de les afficher
     * pour prévenir les attaques XSS (Cross-Site Scripting)
     *
     * @param string $string La chaîne à nettoyer
     * @return string La chaîne nettoyée et sécurisée
     */
    public static function sanitize(string $string): string
    {
        // strip_tags() supprime toutes les balises HTML et PHP
        // trim() supprime les espaces en début et fin de chaîne
        return trim(strip_tags($string));
    }

    /**
     * Échappe une chaîne pour l'affichage HTML en toute sécurité
     *
     * Convertit les caractères spéciaux HTML en entités HTML
     * Par exemple : < devient &lt;  > devient &gt;
     *
     * @param string $string La chaîne à échapper
     * @return string La chaîne échappée
     */
    public static function escape(string $string): string
    {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
}
