<?php
declare(strict_types=1);

require_once __DIR__ . '/Database.php';

/**
 * TP POO — ÉTAPE 3 — Le pattern REPOSITORY
 *
 * Rôle de cette classe : centraliser TOUT le SQL de l'entité « document ».
 * Aucune requête SQL ne doit exister ailleurs dans l'application.
 *
 * NIVEAU 1 : findAll() est fourni. Lisez-le, exécutez-le, comprenez-le.
 * NIVEAU 2 : TODO 1 à 4 — le reste du CRUD.
 *
 * RÈGLE ABSOLUE ET NON NÉGOCIABLE
 * -------------------------------
 * Toute valeur venant de l'utilisateur passe par un MARQUEUR NOMMÉ
 * (:id, :titre...) et jamais par concaténation dans la chaîne SQL.
 *
 *   INTERDIT : "SELECT * FROM documents WHERE id = $id"
 *   ATTENDU  : "SELECT * FROM documents WHERE id = :id"
 *
 * Un seul manquement le jour de la certification = faille d'injection SQL
 * relevée par le jury (critère CP6 : intégrité et confidentialité des données).
 */
class DocumentRepository
{
    // =================================================================
    // NIVEAU 1 — FOURNI
    // =================================================================

    /**
     * Retourne tous les documents du catalogue.
     *
     * Ici, query() suffit : la requête ne contient AUCUNE donnée
     * extérieure, donc aucun risque d'injection. Dès qu'un paramètre
     * entre en jeu, on bascule sur prepare() + execute().
     *
     * @return array<int, array<string, mixed>>
     */
    public function findAll(): array
    {
        $sql  = 'SELECT * FROM documents ORDER BY type, titre';
        $stmt = Database::getConnection()->query($sql);

        return $stmt->fetchAll();
    }

    // =================================================================
    // NIVEAU 2 — À COMPLÉTER
    // =================================================================

    /**
     * TODO 1 — Retourne UN document par son id, ou null s'il n'existe pas.
     *
     * Marche à suivre :
     *   1. $sql avec un marqueur nommé :id
     *   2. $stmt = Database::getConnection()->prepare($sql);
     *   3. $stmt->execute(['id' => $id]);
     *   4. $row = $stmt->fetch();
     *   5. retourner $row, ou null si fetch() a renvoyé false
     *      (astuce : l'opérateur ?: est pratique ici)
     *
     * @return array<string, mixed>|null
     */
    public function findById(int $id): ?array
    {
        // TODO 1 : votre code ici
    }

    /**
     * TODO 2 — Insère un nouveau document et retourne son id.
     *
     * $data contient les clés :
     *   titre, type, annee, auteur_ou_realisateur
     *
     * Marche à suivre :
     *   1. INSERT INTO documents (titre, type, annee, auteur_ou_realisateur)
     *      VALUES (:titre, :type, :annee, :auteur)
     *   2. prepare() puis execute() avec le tableau de valeurs
     *   3. retourner (int) Database::getConnection()->lastInsertId()
     *
     * [PIÈGE] Les noms de colonnes ne sont JAMAIS paramétrables par
     * marqueur. Seules les VALEURS le sont. Si vous avez besoin de rendre
     * un nom de colonne dynamique, il faut le valider contre une liste
     * blanche — sujet abordé en niveau 3.
     */
    public function create(array $data): int
    {
        // TODO 2 : votre code ici
    }

    /**
     * TODO 3 — Met à jour un document existant.
     * Retourne true si au moins une ligne a été modifiée, false sinon.
     *
     * Astuce : $stmt->rowCount() donne le nombre de lignes affectées.
     */
    public function update(int $id, array $data): bool
    {
        // TODO 3 : votre code ici
    }

    /**
     * TODO 4 — Supprime un document. Retourne true si la suppression
     * a bien eu lieu.
     */
    public function delete(int $id): bool
    {
        // TODO 4 : votre code ici
    }

    /**
     * TODO 5 — Change la disponibilité d'un document.
     * Utilisée par emprunter.php.
     *
     * @param bool $disponible true = rendu, false = emprunté
     */
    public function setDisponibilite(int $id, bool $disponible): bool
    {
        // TODO 5 : votre code ici
    }
}
