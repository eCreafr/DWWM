<?php

namespace App\Helpers;

class ImageHelper
{
    private const UPLOAD_DIR = __DIR__ . '/../../public/assets/img/articles/';
    private const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'webp'];
    private const MAX_FILE_SIZE = 5242880; // 5 Mo

    /**
     * Vérifie si un fichier image a été uploadé
     *
     * @param array $file Le tableau $_FILES['nom_du_champ']
     * @return bool
     */
    public static function hasUploadedFile(array $file): bool
    {
        return isset($file['tmp_name']) && !empty($file['tmp_name']) && $file['error'] === UPLOAD_ERR_OK;
    }

    /**
     * Valide un fichier image uploadé
     *
     * @param array $file Le tableau $_FILES['nom_du_champ']
     * @return array ['valid' => bool, 'error' => string|null]
     */
    public static function validateImage(array $file): array
    {
        // Vérifier si un fichier a été uploadé
        if (!self::hasUploadedFile($file)) {
            return ['valid' => false, 'error' => 'Aucun fichier n\'a été uploadé'];
        }

        // Vérifier la taille du fichier
        if ($file['size'] > self::MAX_FILE_SIZE) {
            return ['valid' => false, 'error' => 'Le fichier est trop volumineux (max 5 Mo)'];
        }

        // Récupérer l'extension
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        // Vérifier l'extension
        if (!in_array($extension, self::ALLOWED_EXTENSIONS)) {
            return ['valid' => false, 'error' => 'Format de fichier non autorisé. Seuls les formats JPG et WEBP sont acceptés'];
        }

        // Vérifier le type MIME réel du fichier
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        $allowedMimeTypes = ['image/jpeg', 'image/jpg', 'image/webp'];
        if (!in_array($mimeType, $allowedMimeTypes)) {
            return ['valid' => false, 'error' => 'Le fichier n\'est pas une image valide'];
        }

        return ['valid' => true, 'error' => null];
    }

    /**
     * Upload une image pour un article
     *
     * @param array $file Le tableau $_FILES['nom_du_champ']
     * @param int $articleId L'ID de l'article
     * @return array ['success' => bool, 'filename' => string|null, 'error' => string|null]
     */
    public static function uploadArticleImage(array $file, int $articleId): array
    {
        // Valider l'image
        $validation = self::validateImage($file);
        if (!$validation['valid']) {
            return ['success' => false, 'filename' => null, 'error' => $validation['error']];
        }

        // Créer le dossier s'il n'existe pas
        if (!is_dir(self::UPLOAD_DIR)) {
            mkdir(self::UPLOAD_DIR, 0755, true);
        }

        // Récupérer l'extension
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        // Normaliser l'extension jpeg en jpg
        if ($extension === 'jpeg') {
            $extension = 'jpg';
        }

        // Générer le nom du fichier
        $filename = $articleId . '.' . $extension;
        $filepath = self::UPLOAD_DIR . $filename;

        // Supprimer l'ancienne image si elle existe (avec une extension différente)
        self::deleteArticleImage($articleId);

        // Déplacer le fichier uploadé
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return ['success' => true, 'filename' => $filename, 'error' => null];
        } else {
            return ['success' => false, 'filename' => null, 'error' => 'Erreur lors de l\'upload du fichier'];
        }
    }

    /**
     * Supprime l'image d'un article
     *
     * @param int $articleId L'ID de l'article
     * @return bool
     */
    public static function deleteArticleImage(int $articleId): bool
    {
        $deleted = false;

        // Supprimer toutes les extensions possibles
        foreach (self::ALLOWED_EXTENSIONS as $ext) {
            if ($ext === 'jpeg') continue; // On ne stocke pas en .jpeg, seulement .jpg

            $filepath = self::UPLOAD_DIR . $articleId . '.' . $ext;
            if (file_exists($filepath)) {
                unlink($filepath);
                $deleted = true;
            }
        }

        return $deleted;
    }

    /**
     * Récupère le chemin relatif de l'image d'un article
     *
     * @param int $articleId L'ID de l'article
     * @param string|null $imageName Le nom de l'image stocké en base de données
     * @return string|null Le chemin relatif de l'image ou null si elle n'existe pas
     */
    public static function getArticleImagePath(int $articleId, ?string $imageName): ?string
    {
        if (!$imageName) {
            return null;
        }

        $filepath = self::UPLOAD_DIR . $imageName;
        if (file_exists($filepath)) {
            return '/assets/img/articles/' . $imageName;
        }

        return null;
    }
}
