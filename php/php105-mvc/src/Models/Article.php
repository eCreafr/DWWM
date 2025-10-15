<?php

namespace App\Models;

use App\Database;
use PDO;

/**
 * Classe Article - Modèle représentant un article de presse sportif
 *
 * Cette classe gère toutes les opérations CRUD (Create, Read, Update, Delete)
 * sur les articles dans la base de données.
 *
 * Dans le pattern MVC :
 * - Le Modèle (Model) représente les données et la logique métier
 * - Il interagit directement avec la base de données
 * - Il ne contient aucun code HTML ou logique d'affichage
 */
class Article
{
    /**
     * Connexion à la base de données
     * @var PDO
     */
    private PDO $db;

    /**
     * Constructeur - Initialise la connexion à la base de données
     *
     * On utilise le pattern Singleton de Database pour obtenir
     * la connexion unique à la base de données
     */
    public function __construct()
    {
        // Récupère l'instance unique de Database et sa connexion PDO
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Récupère tous les articles avec leurs résultats de match associés
     *
     * Cette méthode effectue une jointure LEFT JOIN pour récupérer
     * également les informations du match si l'article y est associé.
     *
     * @return array Tableau contenant tous les articles
     */
    public function getAll(): array
    {
        // Requête SQL avec jointure LEFT JOIN :
        // - LEFT JOIN garde tous les articles, même ceux sans match associé
        // - ORDER BY trie par date de publication décroissante (plus récent en premier)
        $query = "
            SELECT
                a.id,
                a.titre,
                a.contenu,
                a.auteur,
                a.date_publication,
                a.match_id,
                r.score,
                r.lieu,
                r.equipe1,
                r.equipe2
            FROM s2_articles_presse a
            LEFT JOIN s2_resultats_sportifs r ON a.match_id = r.id
            ORDER BY a.date_publication DESC
        ";

        $statement = $this->db->prepare($query);
        $statement->execute();

        // fetchAll() retourne un tableau de tous les résultats
        return $statement->fetchAll();
    }

    /**
     * Récupère un article spécifique par son ID
     *
     * @param int $id L'identifiant de l'article à récupérer
     * @return array|false Le tableau des données de l'article, ou false si non trouvé
     */
    public function getById(int $id): array|false
    {
        $query = "
            SELECT
                a.id,
                a.titre,
                a.contenu,
                a.auteur,
                a.date_publication,
                a.match_id,
                r.score,
                r.lieu,
                r.equipe1,
                r.equipe2,
                r.resume
            FROM s2_articles_presse a
            LEFT JOIN s2_resultats_sportifs r ON a.match_id = r.id
            WHERE a.id = :id
        ";

        $statement = $this->db->prepare($query);

        // bindParam lie le paramètre :id à la variable $id de type entier
        // Cela prévient les injections SQL
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        // fetch() retourne une seule ligne (ou false si aucun résultat)
        return $statement->fetch();
    }

    /**
     * Crée un nouvel article dans la base de données
     *
     * @param array $data Tableau associatif contenant les données de l'article
     *                    Clés attendues : titre, contenu, auteur, match_id (optionnel)
     * @return int L'ID de l'article nouvellement créé
     */
    public function create(array $data): int
    {
        $query = "
            INSERT INTO s2_articles_presse
            (titre, contenu, auteur, date_publication, match_id)
            VALUES
            (:titre, :contenu, :auteur, :date_publication, :match_id)
        ";

        $statement = $this->db->prepare($query);

        // Exécution de la requête avec les données passées en paramètre
        $statement->execute([
            'titre' => $data['titre'],
            'contenu' => $data['contenu'],
            'auteur' => $data['auteur'],
            'date_publication' => date('Y-m-d'), // Date actuelle au format SQL
            'match_id' => $data['match_id'] ?? 0, // 0 si pas de match associé
        ]);

        // lastInsertId() retourne l'ID auto-incrémenté du dernier INSERT
        return (int) $this->db->lastInsertId();
    }

    /**
     * Met à jour un article existant
     *
     * @param int $id L'identifiant de l'article à modifier
     * @param array $data Tableau contenant les nouvelles données (titre, contenu)
     * @return bool True si la mise à jour a réussi, false sinon
     */
    public function update(int $id, array $data): bool
    {
        $query = "
            UPDATE s2_articles_presse
            SET
                titre = :titre,
                contenu = :contenu
            WHERE id = :id
        ";

        $statement = $this->db->prepare($query);

        // execute() retourne true en cas de succès, false en cas d'échec
        return $statement->execute([
            'titre' => $data['titre'],
            'contenu' => $data['contenu'],
            'id' => $id,
        ]);
    }

    /**
     * Supprime un article de la base de données
     *
     * @param int $id L'identifiant de l'article à supprimer
     * @return bool True si la suppression a réussi, false sinon
     */
    public function delete(int $id): bool
    {
        $query = "DELETE FROM s2_articles_presse WHERE id = :id";

        $statement = $this->db->prepare($query);

        return $statement->execute(['id' => $id]);
    }

    /**
     * Récupère l'ID du match associé à un article
     *
     * Cette méthode est utile avant de supprimer un article pour savoir
     * si on doit aussi supprimer le match associé
     *
     * @param int $id L'identifiant de l'article
     * @return int|null L'ID du match, ou null si pas de match associé
     */
    public function getMatchId(int $id): ?int
    {
        $query = "SELECT match_id FROM s2_articles_presse WHERE id = :id";

        $statement = $this->db->prepare($query);
        $statement->execute(['id' => $id]);

        $result = $statement->fetch();

        // Retourne l'ID du match s'il existe et est > 0, sinon null
        return ($result && $result['match_id'] > 0) ? (int) $result['match_id'] : null;
    }
}
