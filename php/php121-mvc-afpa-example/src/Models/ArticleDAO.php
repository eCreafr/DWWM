<?php

namespace Afpa\Models;

use Afpa\Tools\Database;
use Afpa\Models\Article;
use DateTime;
use PDO;

/**
 * Classe à utiliser pour requêter la base de données afin de récupérer les informations des articles.
 */
class ArticleDAO {

    /**
     * Permet d'obtenir un tableau de tous les utilisateurs.
     */
    public static function getAll(): array {
        // récupération de l'objet de requêtage
        $pdo = Database::connect();

        // instanciation d'un tableau de stagaires
        $utilisateurs = array();
        $sql = "SELECT * from article";

        $results = $pdo->query($sql);

        // boucle sur toutes les lignes de résultat
        foreach ($results as $row) {
            // récupération des valeurs des enregistrements de la base de données
            $id = $row['id'];
            $texte = $row['texte'];
            $titre = $row['titre'];
            $technologie = $row['technologie'];
            $date = $row['date'];

            // instanciation d'une DateTime pour satisfaire le constructeur d'Article
            $dateTime = new DateTime($date);

            // instanciation d'un nouvel utilisateur
            $article = new Article($id, $titre, $texte, $technologie, $dateTime);
            
            array_push($articles, $article);
        }

        // déconnexion de la base de données
        Database::disconnect();
        return $articles;
    }

    /**
     * Permet d'obtenir un utilisateur par son identifiant
     */
    public static function getById(int $id): Article {
        // récupération de l'objet de requêtage
        $pdo = Database::connect();
        $statement = $pdo->prepare("SELECT * from article WHERE :id");

        // association des paramètres sur la requête
        $statement->bindParam("id", PDO::PARAM_INT);
        $statement->execute();

        // récupération du résultat
        $row = $statement->fetch();

        // récupération des résultats
        $id = $row['id'];
        $texte = $row['texte'];
        $titre = $row['titre'];
        $technologie = $row['technologie'];
        $dateTime = new DateTime($row['date']);

        // instanciation de l'article retrouvé
        $article = new Article($id, $titre, $texte, $technologie, $dateTime);

        return $article;
    }
}