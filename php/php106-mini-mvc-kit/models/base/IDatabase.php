<?php

namespace models\base;

/**
 * Interface IDatabase
 * @package models\base
 * @property string $tableName
 * @property string $primaryKey
 */
interface IDatabase
{
    public function getAll();

    public function getOne(string $id);

    public function deleteOne(string $id);

    public function updateOne(string $id, array $data);
}