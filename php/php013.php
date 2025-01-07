<?php


// exo demo fonctions librairie GD à finaliser


// Charger l'image source
$source = "image.jpg";
$image = imagecreatefromjpeg($source);

// Effet miroir horizontal
function mirrorImage($image)
{
    $width = imagesx($image);
    $height = imagesy($image);
    $mirror = imagecreatetruecolor($width, $height);

    for ($x = 0; $x < $width; $x++) {
        imagecopy($mirror, $image, $width - $x - 1, 0, $x, 0, 1, $height);
    }
    return $mirror;
}

// Conversion noir et blanc
function blackAndWhite($image)
{
    imagefilter($image, IMG_FILTER_GRAYSCALE);
    return $image;
}

// Ajout de texte
function addText($image, $text)
{
    $white = imagecolorallocate($image, 255, 255, 255);
    $font = 5; // Police par défaut
    $width = imagesx($image);
    $height = imagesy($image);

    // Position du texte en bas de l'image
    $textWidth = strlen($text) * imagefontwidth($font);
    $x = ($width - $textWidth) / 2;
    $y = $height - 20;

    imagestring($image, $font, $x, $y, $text, $white);
    return $image;
}

// Application des effets
$mirrorImage = mirrorImage($image);
$bwImage = blackAndWhite(imagecreatefromjpeg($source));
$textImage = addText(imagecreatefromjpeg($source), "Mon texte");

// Sauvegarder les images
imagejpeg($mirrorImage, 'mirror.jpg');
imagejpeg($bwImage, 'bw.jpg');
imagejpeg($textImage, 'text.jpg');

// Libérer la mémoire
imagedestroy($image);
imagedestroy($mirrorImage);
imagedestroy($bwImage);
imagedestroy($textImage);
