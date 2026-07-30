<?php
require_once __DIR__ . '/../models/JeuModel.php';
require_once __DIR__ . '/../models/AdherentModel.php';

class JeuController
{
    private JeuModel $jeuModel;

    public function __construct()
    {
        $this->jeuModel = new JeuModel();
    }

    public function liste(): void
    {
        $jeux = $this->jeuModel->lister();
        // Niveau 3 : necessaire au formulaire d'emprunt affiche par la vue.
        $adherents = (new AdherentModel())->tousLesAdherents();

        require __DIR__ . '/../views/jeux/liste.php';
    }

    public function ajouter(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            require __DIR__ . '/../views/jeux/formulaire.php';
            return;
        }

        [$titre, $editeur, $joueursMin, $joueursMax, $dureeBrute] = $this->lireChampsFormulaire();
        $erreur = $this->validerFormulaire($titre, $joueursMin, $joueursMax, $dureeBrute);

        if ($erreur !== null) {
            $jeu = ['titre' => $titre, 'editeur' => $editeur,
                'nb_joueurs_min' => $joueursMin, 'nb_joueurs_max' => $joueursMax,
                'duree_minutes' => $dureeBrute];
            require __DIR__ . '/../views/jeux/formulaire.php';
            return;
        }

        $this->jeuModel->creer(
            $titre,
            $editeur !== '' ? $editeur : null,
            $joueursMin,
            $joueursMax,
            $dureeBrute === '' ? null : (int) $dureeBrute
        );

        header('Location: index.php?action=liste');
        exit;
    }

    public function modifier(): void
    {
        $id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
        $jeu = $this->jeuModel->trouverParId($id);

        if ($jeu === null) {
            http_response_code(404);
            echo 'Jeu introuvable';
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            require __DIR__ . '/../views/jeux/formulaire.php';
            return;
        }

        [$titre, $editeur, $joueursMin, $joueursMax, $dureeBrute] = $this->lireChampsFormulaire();
        $erreur = $this->validerFormulaire($titre, $joueursMin, $joueursMax, $dureeBrute);

        if ($erreur !== null) {
            $jeu = ['id' => $id, 'titre' => $titre, 'editeur' => $editeur,
                'nb_joueurs_min' => $joueursMin, 'nb_joueurs_max' => $joueursMax,
                'duree_minutes' => $dureeBrute];
            require __DIR__ . '/../views/jeux/formulaire.php';
            return;
        }

        $this->jeuModel->modifier(
            $id,
            $titre,
            $editeur !== '' ? $editeur : null,
            $joueursMin,
            $joueursMax,
            $dureeBrute === '' ? null : (int) $dureeBrute
        );

        header('Location: index.php?action=liste');
        exit;
    }

    public function supprimer(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo 'Methode non autorisee';
            return;
        }

        $id = (int) ($_POST['id'] ?? 0);
        $this->jeuModel->supprimer($id);

        header('Location: index.php?action=liste');
        exit;
    }

    /**
     * @return array{0: string, 1: string, 2: int, 3: int, 4: string}
     */
    private function lireChampsFormulaire(): array
    {
        $titre = trim($_POST['titre'] ?? '');
        $editeur = trim($_POST['editeur'] ?? '');
        $joueursMin = (int) ($_POST['nb_joueurs_min'] ?? 0);
        $joueursMax = (int) ($_POST['nb_joueurs_max'] ?? 0);
        $dureeBrute = trim($_POST['duree_minutes'] ?? '');

        return [$titre, $editeur, $joueursMin, $joueursMax, $dureeBrute];
    }

    private function validerFormulaire(string $titre, int $joueursMin, int $joueursMax, string $dureeBrute): ?string
    {
        if ($titre === '') {
            return 'Le titre est obligatoire.';
        }

        if ($joueursMin < 1) {
            return 'Le nombre de joueurs minimum doit etre au moins 1.';
        }

        if ($joueursMax < $joueursMin) {
            return 'Le nombre de joueurs maximum doit etre superieur ou egal au minimum.';
        }

        if ($dureeBrute !== '' && (!ctype_digit($dureeBrute) || (int) $dureeBrute < 1)) {
            return 'La duree doit etre un nombre de minutes positif.';
        }

        return null;
    }
}
