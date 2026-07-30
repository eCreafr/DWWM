<?php
declare(strict_types=1);

require_once __DIR__ . '/../Database.php';

/**
 * ===================================================================
 * CORRIGÉ FORMATEUR — ÉTAPE 3 — DocumentRepository
 * ===================================================================
 * Niveaux 1 et 2 complets. Les extensions de niveau 3 (hydratation,
 * transaction, agrégations) sont dans /extensions-niveau3/.
 */
class DocumentRepository
{
    /** Liste blanche des colonnes modifiables — voir note en bas de fichier. */
    private const COLONNES_AUTORISEES = [
        'titre', 'type', 'annee', 'auteur_ou_realisateur',
        'isbn', 'duree_minutes', 'plateforme', 'pegi', 'disponible',
    ];

    // =================================================================
    // NIVEAU 1 — fourni au stagiaire
    // =================================================================

    /** @return array<int, array<string, mixed>> */
    public function findAll(): array
    {
        $sql  = 'SELECT * FROM documents ORDER BY type, titre';
        $stmt = Database::getConnection()->query($sql);

        return $stmt->fetchAll();
    }

    // =================================================================
    // NIVEAU 2 — TODO 1 à 5 corrigés
    // =================================================================

    /**
     * TODO 1 corrigé.
     *
     * Points d'attention pour la correction :
     *   - prepare() et non query() : il y a un paramètre.
     *   - fetch() renvoie `false` quand aucune ligne ne correspond ;
     *     l'opérateur `?:` convertit ce false en null, conforme au
     *     type de retour déclaré `?array`.
     *
     * @return array<string, mixed>|null
     */
    public function findById(int $id): ?array
    {
        $sql  = 'SELECT * FROM documents WHERE id = :id';
        $stmt = Database::getConnection()->prepare($sql);
        $stmt->execute(['id' => $id]);

        return $stmt->fetch() ?: null;
    }

    /**
     * TODO 2 corrigé.
     *
     * lastInsertId() retourne une string : le cast (int) est nécessaire
     * pour respecter le type de retour déclaré.
     *
     * @param array<string, mixed> $data
     */
    public function create(array $data): int
    {
        $sql = 'INSERT INTO documents (titre, type, annee, auteur_ou_realisateur)
                VALUES (:titre, :type, :annee, :auteur)';

        $stmt = Database::getConnection()->prepare($sql);
        $stmt->execute([
            'titre'  => $data['titre'],
            'type'   => $data['type'],
            'annee'  => $data['annee'],
            'auteur' => $data['auteur_ou_realisateur'],
        ]);

        return (int) Database::getConnection()->lastInsertId();
    }

    /**
     * TODO 3 corrigé — version simple, attendue au niveau 2.
     *
     * @param array<string, mixed> $data
     */
    public function update(int $id, array $data): bool
    {
        $sql = 'UPDATE documents
                SET titre = :titre,
                    type = :type,
                    annee = :annee,
                    auteur_ou_realisateur = :auteur
                WHERE id = :id';

        $stmt = Database::getConnection()->prepare($sql);
        $stmt->execute([
            'titre'  => $data['titre'],
            'type'   => $data['type'],
            'annee'  => $data['annee'],
            'auteur' => $data['auteur_ou_realisateur'],
            'id'     => $id,
        ]);

        return $stmt->rowCount() > 0;
    }

    /** TODO 4 corrigé. */
    public function delete(int $id): bool
    {
        $sql  = 'DELETE FROM documents WHERE id = :id';
        $stmt = Database::getConnection()->prepare($sql);
        $stmt->execute(['id' => $id]);

        return $stmt->rowCount() > 0;
    }

    /**
     * TODO 5 corrigé.
     *
     * Le cast (int) du booléen est explicite : MySQL stocke un TINYINT(1).
     * PDO convertirait `false` en chaîne vide, ce qui produirait 0 mais
     * de façon non maîtrisée. Écrire le cast rend l'intention lisible.
     */
    public function setDisponibilite(int $id, bool $disponible): bool
    {
        $sql  = 'UPDATE documents SET disponible = :disponible WHERE id = :id';
        $stmt = Database::getConnection()->prepare($sql);
        $stmt->execute([
            'disponible' => (int) $disponible,
            'id'         => $id,
        ]);

        return $stmt->rowCount() > 0;
    }

    // =================================================================
    // VARIANTE PÉDAGOGIQUE — update dynamique par liste blanche
    // =================================================================

    /**
     * À montrer UNIQUEMENT aux stagiaires qui demandent « comment faire
     * un update partiel ? ». Illustre pourquoi un nom de colonne ne peut
     * pas passer par un marqueur nommé, et comment le sécuriser malgré
     * tout : par une LISTE BLANCHE.
     *
     * @param array<string, mixed> $data
     */
    public function updatePartiel(int $id, array $data): bool
    {
        $champs = [];
        $params = ['id' => $id];

        foreach ($data as $colonne => $valeur) {

            // Sans ce filtre, un tableau $data contrôlé par l'utilisateur
            // permettrait d'injecter du SQL via le NOM de colonne.
            if (!in_array($colonne, self::COLONNES_AUTORISEES, true)) {
                continue;
            }

            $champs[] = "$colonne = :$colonne";
            $params[$colonne] = $valeur;
        }

        if ($champs === []) {
            return false;
        }

        $sql  = 'UPDATE documents SET ' . implode(', ', $champs) . ' WHERE id = :id';
        $stmt = Database::getConnection()->prepare($sql);
        $stmt->execute($params);

        return $stmt->rowCount() > 0;
    }
}
