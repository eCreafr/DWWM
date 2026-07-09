<?php
require_once __DIR__ . '/../models/JeuModel.php';

class JeuController
{
    private JeuModel $jeuModel;

    public function __construct()
    {
        $this->jeuModel = new JeuModel();
    }

    public function liste(): void
    {
        // TODO Niveau 1 :
        // 1. Recuperer les jeux via $this->jeuModel->tousLesJeux()
        // 2. Transmettre le resultat a la vue (require __DIR__ . '/../views/jeux/liste.php')
        //
        // Rappel : aucun SQL, aucun HTML dans ce fichier.
    }

    public function ajouter(): void
    {
        // TODO Niveau 2 :
        // - si methode GET : afficher le formulaire vide
        // - si methode POST : valider les champs, appeler $this->jeuModel->creer(...),
        //   puis rediriger vers ?action=liste (pattern Post/Redirect/Get)
    }

    public function modifier(): void
    {
        // TODO Niveau 2 : meme logique que ajouter(), mais pre-remplie
        // avec $this->jeuModel->trouverParId($id)
    }

    public function supprimer(): void
    {
        // TODO Niveau 2 : appeler $this->jeuModel->supprimer($id) puis rediriger
    }
}
