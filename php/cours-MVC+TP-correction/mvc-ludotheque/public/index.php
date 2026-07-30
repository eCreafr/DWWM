<?php
// ============================================================
// Front controller — seul point d'entree de l'application
// ============================================================

require __DIR__ . '/../app/controllers/JeuController.php';
require __DIR__ . '/../app/controllers/EmpruntController.php';

$action = $_GET['action'] ?? 'liste';
$controleur = new JeuController();

switch ($action) {
    case 'liste':
        $controleur->liste();
        break;

    case 'ajouter':
        $controleur->ajouter();
        break;

    case 'modifier':
        $controleur->modifier();
        break;

    case 'supprimer':
        $controleur->supprimer();
        break;

    // Niveau 3 : gestion des emprunts, deleguee a un controleur dedie.
    case 'emprunter':
        (new EmpruntController())->emprunter();
        break;

    case 'retourner':
        (new EmpruntController())->retourner();
        break;

    default:
        http_response_code(404);
        echo 'Page introuvable';
}
