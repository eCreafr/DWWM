<?php

// Fonction slugify pour préparer nettoyer le titre dans les  url SEO friendly
function slugify($text)
{
    // Remplace les caractères non alphanumériques par un tiret
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    // Translitération
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    // Supprime les caractères indésirables
    $text = preg_replace('~[^-\w]+~', '', $text);
    // Supprime les tirets en début et fin
    $text = trim($text, '-');
    // Convertit en minuscules
    $text = strtolower($text);
    return empty($text) ? 'n-a' : $text;
}

// Fonction pour construire des URL SEO friendly des articles dans un sous dossier virtuel avec parfois le score si dispo
function createArticleUrl($id, $titre, $score = null)
{
    $slug = slugify($titre);

    // Ajouter le score dans l'URL s'il est dispo
    if (!is_null($score)) {
        return '/article/' . $id . '-' . $slug . '-' . $score . '.html';
    }

    // Sinon, retourner l'URL sans score tant pis
    return '/article/' . $id . '-' . $slug . '.html';
}


// Tronquer fonction
function truncateString($string, $length = 20)
{
    if (strlen($string) > $length) {
        return substr($string, 0, $length) . '...';
    }
    return $string;
}
