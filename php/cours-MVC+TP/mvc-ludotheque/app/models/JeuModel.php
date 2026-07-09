<?php

class JeuModel
{
    // TODO Niveau 1 :
    // Renvoyer un tableau PHP en dur (voir data-test-jeux.php fourni a la racine
    // du dossier de depart : copiez-collez son contenu ici).
    public function tousLesJeux(): array
    {
        return [];
    }

    // ------------------------------------------------------------------
    // TODO Niveau 2 : brancher ces methodes sur PDO (voir config/database.php)
    // Rappel : uniquement des requetes preparees, jamais de concatenation
    // de variable dans une chaine SQL.
    // ------------------------------------------------------------------

    public function lister(): array
    {
        // TODO
        return [];
    }

    public function trouverParId(int $id): ?array
    {
        // TODO
        return null;
    }

    public function creer(string $titre, int $joueursMin, int $joueursMax): void
    {
        // TODO
    }

    public function modifier(int $id, string $titre, int $joueursMin, int $joueursMax): void
    {
        // TODO
    }

    public function supprimer(int $id): void
    {
        // TODO
    }
}
