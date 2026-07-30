<?php
declare(strict_types=1);

/**
 * ===================================================================
 * EXTENSION NIVEAU 3 — ÉTAPE 3 — HYDRATATION
 * ===================================================================
 * Objectif : le Repository ne retourne plus des tableaux associatifs,
 * mais de vrais objets Livre / Dvd / JeuVideo.
 *
 * C'est le chaînon manquant entre l'étape 2 (les classes métier) et
 * l'étape 3 (l'accès aux données). Les stagiaires qui font cette
 * extension comprennent d'un coup à quoi servait l'étape 2.
 *
 * C'est aussi, en simplifié, ce que fait un ORM (Doctrine, Eloquent).
 * Bon argument à sortir devant le jury.
 */

require_once __DIR__ . '/../etape2/src/Document.php';
require_once __DIR__ . '/../etape2/src/Empruntable.php';
require_once __DIR__ . '/../etape2/src/Livre.php';
require_once __DIR__ . '/../etape2/src/Dvd.php';
require_once __DIR__ . '/../etape2/src/JeuVideo.php';
require_once __DIR__ . '/../etape3/src/Database.php';

class DocumentRepositoryHydrate
{
    /**
     * Transforme une ligne de la base en objet métier du bon type.
     *
     * Cette méthode est PRIVÉE : elle est un détail d'implémentation
     * du Repository. L'appelant n'a pas à savoir comment on fabrique
     * les objets, seulement qu'il en reçoit.
     *
     * @param array<string, mixed> $row
     */
    private function hydrate(array $row): Document
    {
        return match ($row['type']) {

            'livre' => new Livre(
                (string) $row['titre'],
                (int) $row['annee'],
                (string) $row['auteur_ou_realisateur']
            ),

            'dvd' => new Dvd(
                (string) $row['titre'],
                (int) $row['annee'],
                (string) $row['auteur_ou_realisateur'],
                (int) ($row['duree_minutes'] ?? 0)
            ),

            'jeu_video' => new JeuVideo(
                (string) $row['titre'],
                (int) $row['annee'],
                (string) ($row['plateforme'] ?? 'Inconnue'),
                (int) ($row['pegi'] ?? 3)
            ),

            // Le bras par défaut n'est pas optionnel : si un nouveau type
            // apparaît en base sans que le code soit mis à jour, on veut
            // une erreur explicite, pas un comportement silencieux.
            default => throw new RuntimeException(
                'Type de document inconnu : ' . (string) $row['type']
            ),
        };
    }

    /** @return array<int, Document> */
    public function findAll(): array
    {
        $stmt = Database::getConnection()->query(
            'SELECT * FROM documents ORDER BY type, titre'
        );

        // array_map applique hydrate() à chaque ligne.
        return array_map([$this, 'hydrate'], $stmt->fetchAll());
    }

    public function findById(int $id): ?Document
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT * FROM documents WHERE id = :id'
        );
        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch();

        return $row ? $this->hydrate($row) : null;
    }

    /** @return array<int, Document> */
    public function findDisponibles(): array
    {
        $stmt = Database::getConnection()->query(
            'SELECT * FROM documents WHERE disponible = 1 ORDER BY titre'
        );

        return array_map([$this, 'hydrate'], $stmt->fetchAll());
    }

    /**
     * Agrégation SQL — compte les documents par type.
     *
     * @return array<string, int>
     */
    public function countByType(): array
    {
        $stmt = Database::getConnection()->query(
            'SELECT type, COUNT(*) AS nb FROM documents GROUP BY type'
        );

        $resultat = [];
        foreach ($stmt->fetchAll() as $ligne) {
            $resultat[(string) $ligne['type']] = (int) $ligne['nb'];
        }

        return $resultat;
    }
}

// ---------------------------------------------------------------
// Démonstration : la boucle est identique à celle de l'étape 2.
// C'est tout l'intérêt — le code d'affichage ne change pas selon
// que les objets viennent d'un tableau en dur ou de la base.
// ---------------------------------------------------------------
//
// $repo = new DocumentRepositoryHydrate();
// foreach ($repo->findAll() as $document) {
//     echo $document->getDescription() . PHP_EOL;
// }
