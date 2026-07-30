<?php
require_once __DIR__ . '/../models/JeuModel.php';
require_once __DIR__ . '/../models/EmpruntModel.php';

// Niveau 3 : orchestre JeuModel et EmpruntModel pour la regle metier
// "un jeu ne peut etre emprunte que s'il est disponible", a cheval sur deux entites.
// Cette regle ne doit vivre ni dans le Controleur, ni dans un seul des deux Modeles.
class EmpruntService
{
    private JeuModel $jeuModel;
    private EmpruntModel $empruntModel;

    public function __construct()
    {
        $this->jeuModel = new JeuModel();
        $this->empruntModel = new EmpruntModel();
    }

    public function emprunter(int $jeuId, int $adherentId): void
    {
        $jeu = $this->jeuModel->trouverParId($jeuId);

        if ($jeu === null) {
            throw new RuntimeException('Jeu introuvable.');
        }

        if (!$jeu['disponible']) {
            throw new RuntimeException('Ce jeu n\'est pas disponible actuellement.');
        }

        $this->empruntModel->creerEmprunt($jeuId, $adherentId);
        $this->jeuModel->changerDisponibilite($jeuId, false);
    }

    public function retourner(int $jeuId): void
    {
        $jeu = $this->jeuModel->trouverParId($jeuId);

        if ($jeu === null) {
            throw new RuntimeException('Jeu introuvable.');
        }

        $this->empruntModel->cloturerEmprunt($jeuId);
        $this->jeuModel->changerDisponibilite($jeuId, true);
    }
}
