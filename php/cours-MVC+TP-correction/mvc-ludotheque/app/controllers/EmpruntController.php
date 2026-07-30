<?php
require_once __DIR__ . '/../services/EmpruntService.php';

// Niveau 3 : orchestration HTTP des emprunts, delegue la regle metier a EmpruntService.
class EmpruntController
{
    private EmpruntService $empruntService;

    public function __construct()
    {
        $this->empruntService = new EmpruntService();
    }

    public function emprunter(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo 'Methode non autorisee';
            return;
        }

        $jeuId = (int) ($_POST['jeu_id'] ?? 0);
        $adherentId = (int) ($_POST['adherent_id'] ?? 0);

        if ($adherentId < 1) {
            header('Location: index.php?action=liste&erreur=' . urlencode('Choisissez un adherent avant d\'emprunter.'));
            exit;
        }

        try {
            $this->empruntService->emprunter($jeuId, $adherentId);
        } catch (RuntimeException $e) {
            header('Location: index.php?action=liste&erreur=' . urlencode($e->getMessage()));
            exit;
        }

        header('Location: index.php?action=liste');
        exit;
    }

    public function retourner(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo 'Methode non autorisee';
            return;
        }

        $jeuId = (int) ($_POST['jeu_id'] ?? 0);

        try {
            $this->empruntService->retourner($jeuId);
        } catch (RuntimeException $e) {
            header('Location: index.php?action=liste&erreur=' . urlencode($e->getMessage()));
            exit;
        }

        header('Location: index.php?action=liste');
        exit;
    }
}
