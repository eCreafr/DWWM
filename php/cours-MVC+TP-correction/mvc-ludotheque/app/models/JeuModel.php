<?php

class JeuModel
{
    private PDO $pdo;

    public function __construct()
    {
        require __DIR__ . '/../../config/database.php';
        $this->pdo = $pdo;
    }

    // Niveau 1 : donnees en dur, avant tout branchement PDO.
    // Conservee pour reference, non utilisee une fois lister() branchee sur la BDD.
    public function tousLesJeux(): array
    {
        return [
            ['id' => 1, 'titre' => 'Catan',       'editeur' => 'Kosmos',           'nb_joueurs_min' => 3, 'nb_joueurs_max' => 4, 'duree_minutes' => 90, 'disponible' => 1],
            ['id' => 2, 'titre' => '7 Wonders',   'editeur' => 'Repos Production', 'nb_joueurs_min' => 3, 'nb_joueurs_max' => 7, 'duree_minutes' => 30, 'disponible' => 1],
            ['id' => 3, 'titre' => 'Splendor',    'editeur' => 'Space Cowboys',    'nb_joueurs_min' => 2, 'nb_joueurs_max' => 4, 'duree_minutes' => 30, 'disponible' => 0],
            ['id' => 4, 'titre' => 'Azul',        'editeur' => 'Plan B Games',     'nb_joueurs_min' => 2, 'nb_joueurs_max' => 4, 'duree_minutes' => 45, 'disponible' => 1],
            ['id' => 5, 'titre' => 'Carcassonne', 'editeur' => 'Hans im Gluck',    'nb_joueurs_min' => 2, 'nb_joueurs_max' => 5, 'duree_minutes' => 35, 'disponible' => 1],
        ];
    }

    // ------------------------------------------------------------------
    // Niveau 2 : CRUD branche sur PDO, uniquement des requetes preparees.
    // ------------------------------------------------------------------

    public function lister(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM jeux ORDER BY titre ASC');

        return $stmt->fetchAll();
    }

    public function trouverParId(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM jeux WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $jeu = $stmt->fetch();

        return $jeu === false ? null : $jeu;
    }

    public function creer(string $titre, ?string $editeur, int $joueursMin, int $joueursMax, ?int $dureeMinutes): void
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO jeux (titre, editeur, nb_joueurs_min, nb_joueurs_max, duree_minutes, disponible)
             VALUES (:titre, :editeur, :joueurs_min, :joueurs_max, :duree, 1)'
        );

        $stmt->bindValue(':titre', $titre, PDO::PARAM_STR);
        $stmt->bindValue(':editeur', $editeur, PDO::PARAM_STR);
        $stmt->bindValue(':joueurs_min', $joueursMin, PDO::PARAM_INT);
        $stmt->bindValue(':joueurs_max', $joueursMax, PDO::PARAM_INT);
        $stmt->bindValue(':duree', $dureeMinutes, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function modifier(int $id, string $titre, ?string $editeur, int $joueursMin, int $joueursMax, ?int $dureeMinutes): void
    {
        $stmt = $this->pdo->prepare(
            'UPDATE jeux
             SET titre = :titre, editeur = :editeur, nb_joueurs_min = :joueurs_min,
                 nb_joueurs_max = :joueurs_max, duree_minutes = :duree
             WHERE id = :id'
        );

        $stmt->bindValue(':titre', $titre, PDO::PARAM_STR);
        $stmt->bindValue(':editeur', $editeur, PDO::PARAM_STR);
        $stmt->bindValue(':joueurs_min', $joueursMin, PDO::PARAM_INT);
        $stmt->bindValue(':joueurs_max', $joueursMax, PDO::PARAM_INT);
        $stmt->bindValue(':duree', $dureeMinutes, PDO::PARAM_INT);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function supprimer(int $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM jeux WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    // ------------------------------------------------------------------
    // Niveau 3 : bascule de disponibilite, utilisee par EmpruntService.
    // ------------------------------------------------------------------

    public function changerDisponibilite(int $id, bool $disponible): void
    {
        $stmt = $this->pdo->prepare('UPDATE jeux SET disponible = :disponible WHERE id = :id');
        $stmt->bindValue(':disponible', $disponible ? 1 : 0, PDO::PARAM_INT);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
}
