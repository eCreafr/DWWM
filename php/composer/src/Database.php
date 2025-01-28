<?php

namespace App;

class Database
{
    private $pdo;

    public function __construct($host, $dbname, $user, $pass)
    {
        $this->pdo = new \PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function query($sql)
    {
        return $this->pdo->exec($sql);
    }

    public function testConnection()
    {
        try {
            $this->pdo->query('SELECT 1');
            return true;
        } catch (\PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }

    public function create($table, $data)
    {
        $keys = implode(',', array_keys($data));
        $placeholders = ':' . implode(',:', array_keys($data));
        $stmt = $this->pdo->prepare("INSERT INTO $table ($keys) VALUES ($placeholders)");
        return $stmt->execute($data);
    }

    public function read($table, $id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM $table WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function update($table, $id, $data)
    {
        $updates = implode(', ', array_map(fn($key) => "$key = :$key", array_keys($data)));
        $stmt = $this->pdo->prepare("UPDATE $table SET $updates WHERE id = :id");
        $data['id'] = $id;
        return $stmt->execute($data);
    }

    public function delete($table, $id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM $table WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
