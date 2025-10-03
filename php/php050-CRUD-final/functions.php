<?php
// ============================================================
// FICHIER DES FONCTIONS UTILITAIRES
// ============================================================
// Ce fichier contient des fonctions réutilisables dans
// différentes pages du site.
// ============================================================

// ------------------------------------------------------------
// Fonction slugify : transforme un texte en URL "propre"
// ------------------------------------------------------------
// Exemple : "L'équipe de France gagne 3-0" devient "l-equipe-de-france-gagne-3-0"
// Cette transformation rend les URL plus lisibles et meilleures pour le SEO
function slugify($text)
{
    // Étape 1 : Remplace tous les caractères non alphanumériques par un tiret
    // \pL = lettres Unicode (pour gérer les accents)
    // \d = chiffres
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);

    // Étape 2 : Translitération (convertit les caractères accentués en ASCII)
    // é devient e, à devient a, etc.
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

    // Étape 3 : Supprime les caractères indésirables qui restent
    // On garde uniquement les tirets et les caractères alphanumériques
    $text = preg_replace('~[^-\w]+~', '', $text);

    // Étape 4 : Supprime les tirets en début et fin de chaîne
    $text = trim($text, '-');

    // Étape 5 : Convertit tout en minuscules pour uniformiser
    $text = strtolower($text);

    // Si après tout ça le texte est vide, on retourne 'n-a' (non applicable)
    return empty($text) ? 'n-a' : $text;
}

// ------------------------------------------------------------
// Fonction createArticleUrl : construit une URL SEO friendly
// ------------------------------------------------------------
// Crée des URLs du type : /article/42-psg-gagne-a-domicile-3-1.html
// Paramètres :
// - $id : identifiant de l'article (obligatoire)
// - $titre : titre de l'article (obligatoire)
// - $score : score du match (facultatif)
function createArticleUrl($id, $titre, $score = null)
{
    // On transforme d'abord le titre en slug (URL propre)
    $slug = slugify($titre);

    // Si un score est disponible, on l'ajoute dans l'URL
    // Cela rend l'URL encore plus informative
    if (!is_null($score)) {
        return '/article/' . $id . '-' . $slug . '-' . $score . '.html';
    }

    // Sinon, on retourne l'URL sans le score
    return '/article/' . $id . '-' . $slug . '.html';
}

// ------------------------------------------------------------
// Fonction truncateString : tronque (raccourcit) un texte
// ------------------------------------------------------------
// Utilisée pour afficher un extrait d'article dans les listes
// Paramètres :
// - $string : le texte à tronquer
// - $length : longueur maximale souhaitée (20 caractères par défaut)
// Retourne : le texte tronqué avec '...' à la fin si nécessaire
function truncateString($string, $length = 20)
{
    // Si le texte est plus long que la longueur demandée
    if (strlen($string) > $length) {
        // On coupe le texte et on ajoute '...' à la fin
        return substr($string, 0, $length) . '...';
    }
    // Sinon, on retourne le texte tel quel
    return $string;
}
