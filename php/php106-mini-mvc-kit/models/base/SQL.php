<?php

namespace models\base;

use PDO;

class SQL implements IDatabase
{
    protected $tableName = '';
    protected $primaryKey = '';

    /**
     * @var $pdo PDO|null
     */
    private static ?PDO $pdo = null;

    /**
     * @return PDO
     */
    public static function getPdo(): PDO
    {
        if (SQL::$pdo == null) {
            SQL::$pdo = Database::connect();
        }

        return self::$pdo;
    }

    /**
     * @param String $tableName Nom de la table
     * @param String $primaryKey Clé primaire de la table
     */
    function __construct(string $tableName, string $primaryKey = 'id')
    {
        $this->tableName = $tableName;
        $this->primaryKey = $primaryKey;
    }

    /**
     * Retourne l'ensemble des enregistrements présent en base de données (pour la table $tableName)
     * @return array|null
     */
    public function getAll(): array|null
    {
        $stmt = SQL::getPdo()->prepare("SELECT * FROM {$this->tableName};");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Permet la récupération d'un enregistrement en base de données
     * @param String $id
     * @return \stdClass|null
     */
    public function getOne(string $id): \stdClass|null
    {
        $stmt = SQL::getPdo()->prepare("SELECT * FROM {$this->tableName} WHERE {$this->primaryKey} = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Supprime l'enregistrement $id dans la table $tableName
     * @param $id
     * @return bool
     */
    public function deleteOne(string $id): bool
    {
        $stmt = SQL::getPdo()->prepare("DELETE FROM {$this->tableName} WHERE {$this->primaryKey} = ? LIMIT 1");
        return $stmt->execute([$id]);
    }

    /**
     * Permet la mise à jour de l'ensemble des champs passée en paramètre dans $data pour l'enregistrement à $id.
     * @param $id
     * @param array $data
     * @return bool
     */
    public function updateOne(string $id, array $data = array()): bool
    {
        $query = "UPDATE {$this->tableName} SET ";

        foreach ($data as $columnName => $columnValue) {
            $query .= $columnName . " = :$columnName, ";
        }
        $query = rtrim($query, ", ");

        $query .= " WHERE {$this->primaryKey} = :id";

        $stmt = SQL::getPdo()->prepare($query);
        return $stmt->execute(array_merge(["id" => $id], $data));
    }
}