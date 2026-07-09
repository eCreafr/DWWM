<?php
// ============================================================
// Front controller — seul point d'entree de l'application
// ============================================================

require __DIR__ . '/../app/controllers/JeuController.php';

$action = $_GET['action'] ?? 'liste';
$controleur = new JeuController();

switch ($action) {
    case 'liste':
        // TODO Niveau 1 : appeler la methode du controleur qui affiche la liste
        // $controleur->liste();
        break;

    // TODO Niveau 2 : ajoutez les routes 'ajouter', 'modifier', 'supprimer'
    // au fur et a mesure du TP. Chaque route appelle une methode du controleur,
    // jamais de logique metier ou de HTML ici.

    default:
        http_response_code(404);
        echo 'Page introuvable';
}
