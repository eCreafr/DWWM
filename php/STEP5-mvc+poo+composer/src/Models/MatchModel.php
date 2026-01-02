<?php

namespace App\Models;

use App\Database;
use PDO;

/**
 * Classe MatchModel - Modèle représentant un résultat sportif
 *
 * Cette classe gère les opérations CRUD sur les résultats de matchs
 * dans la table s2_resultats_sportifs
 *
 * Note : Nommée MatchModel au lieu de Match car "match" est un mot-clé
 * réservé en PHP 8.0+ (utilisé pour les match expressions)
 */
class MatchModel
{
    /**
     * Connexion à la base de données
     * @var PDO
     */
    private PDO $db;

    /**
     * Constructeur - Initialise la connexion à la base de données
     */
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Crée un nouveau résultat de match dans la base de données
     *
     * @param array $data Tableau contenant les données du match
     *                    Clés : equipe1, equipe2, score, lieu, resume, date_match
     * @return int L'ID du match nouvellement créé
     */
    public function create(array $data): int
    {
        $query = "
            INSERT INTO s2_resultats_sportifs
            (equipe1, equipe2, score, lieu, resume, date_match)
            VALUES
            (:equipe1, :equipe2, :score, :lieu, :resume, :date_match)
        ";

        $statement = $this->db->prepare($query);

        $statement->execute([
            'equipe1' => $data['equipe1'],
            'equipe2' => $data['equipe2'],
            'score' => $data['score'],
            'lieu' => $data['lieu'],
            'resume' => $data['resume'] ?? '', // Résumé facultatif
            'date_match' => $data['date_match'] ?? date('Y-m-d'),
        ]);

        // Retourne l'ID du match qui vient d'être inséré
        return (int) $this->db->lastInsertId();
    }

    /**
     * Met à jour un match existant
     *
     * @param int $id L'identifiant du match à modifier
     * @param array $data Tableau contenant les nouvelles données
     * @return bool True si la mise à jour a réussi
     */
    public function update(int $id, array $data): bool
    {
        $query = "
            UPDATE s2_resultats_sportifs
            SET
                equipe1 = :equipe1,
                equipe2 = :equipe2,
                score = :score,
                lieu = :lieu,
                resume = :resume
            WHERE id = :id
        ";

        $statement = $this->db->prepare($query);

        return $statement->execute([
            'equipe1' => $data['equipe1'],
            'equipe2' => $data['equipe2'],
            'score' => $data['score'],
            'lieu' => $data['lieu'],
            'resume' => $data['resume'] ?? '',
            'id' => $id,
        ]);
    }

    /**
     * Supprime un match de la base de données
     *
     * @param int $id L'identifiant du match à supprimer
     * @return bool True si la suppression a réussi
     */
    public function delete(int $id): bool
    {
        $query = "DELETE FROM s2_resultats_sportifs WHERE id = :id";

        $statement = $this->db->prepare($query);

        return $statement->execute(['id' => $id]);
    }

    /**
     * Récupère un match par son ID
     *
     * @param int $id L'identifiant du match
     * @return array|false Les données du match ou false si non trouvé
     */
    public function getById(int $id): array|false
    {
        $query = "SELECT * FROM s2_resultats_sportifs WHERE id = :id";

        $statement = $this->db->prepare($query);
        $statement->execute(['id' => $id]);

        return $statement->fetch();
    }
}
