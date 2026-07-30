<?php
declare(strict_types=1);

/**
 * ===================================================================
 * AMORCE MVC — CONTROLLER
 * ===================================================================
 * Rôle du contrôleur, à faire énoncer par les stagiaires :
 *   1. récupérer et VALIDER les données de la requête
 *   2. demander au Model (via le Repository) ce dont il a besoin
 *   3. choisir la vue et lui passer les données préparées
 *
 * Ce qu'un contrôleur NE FAIT PAS :
 *   - pas de SQL (c'est le Repository)
 *   - pas de HTML (c'est la vue)
 *
 * Si du SQL ou du <div> apparaît ici, l'architecture est cassée.
 */
class DocumentController
{
    private DocumentRepository $repository;

    public function __construct()
    {
        $this->repository = new DocumentRepository();
    }

    public function liste(): void
    {
        try {
            $documents = $this->repository->findAll();
        } catch (Throwable $e) {
            error_log($e->getMessage());
            $documents = [];
            $erreur = 'Le catalogue est momentanément indisponible.';
        }

        // Les variables définies ici seront visibles dans la vue :
        // le require se fait dans la même portée.
        $titrePage = 'Catalogue';

        require __DIR__ . '/../../views/liste.php';
    }

    public function detail(): void
    {
        // Validation de l'entrée : c'est le rôle du contrôleur,
        // pas celui de la vue ni du repository.
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if ($id === false || $id === null || $id <= 0) {
            header('Location: index.php?action=liste');
            exit;
        }

        $document = $this->repository->findById($id);

        if ($document === null) {
            http_response_code(404);
            $titrePage = 'Document introuvable';
            require __DIR__ . '/../../views/404.php';
            return;
        }

        $titrePage = $document['titre'];
        require __DIR__ . '/../../views/detail.php';
    }
}
