<?php

namespace models;

use models\base\SQL;

class SampleModel extends SQL
{
    public function __construct()
    {
        parent::__construct('votre-table', 'cle-de-votre-table');
    }

    /**
     * Méthode d'exemple permettant l'accès aux données avec une
     * requête préparée.
     */
    public function getSampleData(string $filterEl): \stdClass
    {
        $stmt = $this->getPdo()->prepare("SELECT * from `votre-table` WHERE col = ?");
        $stmt->execute([$filterEl]);
        return $stmt->fetch(\PDO::FETCH_OBJ);
    }
}