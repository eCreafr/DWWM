<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Database;

class DatabaseTest extends TestCase
{
    private $db;
    private $lastInsertedIds = [];

    protected function setUp(): void
    {
        $this->db = new Database(getenv('DB_HOST'), getenv('DB_NAME'), getenv('DB_USER'), getenv('DB_PASS'));

        /* optionnel :
        // Nettoyer la table avant chaque test
        $this->db->query("TRUNCATE TABLE users"); 
        */

        // Insérer les données de test sans spécifier l'ID
        $userData = ['name' => 'John Doe', 'email' => 'john@example.com'];
        $this->db->create('users', $userData);
        // Stocker l'ID auto-incrémenté
        $this->lastInsertedIds[] = $this->db->lastInsertId();
    }

    protected function tearDown(): void
    {
        if ($this->db) {
            // Nettoyer tous les utilisateurs créés pendant les tests
            foreach ($this->lastInsertedIds as $id) {
                $this->db->delete('users', $id);
            }
        }
    }

    public function testDatabaseConnection()
    {
        $this->assertTrue($this->db->testConnection(), "La connexion à la base de données a échoué");
    }

    public function testCreate()
    {
        $userData = ['name' => 'Jane Doe', 'email' => 'jane@example.com'];
        $result = $this->db->create('users', $userData);
        $this->assertTrue($result);

        // Récupérer l'ID auto-incrémenté
        $newId = $this->db->lastInsertId();
        $this->lastInsertedIds[] = $newId;

        // Vérifier que l'utilisateur a bien été créé
        $user = $this->db->read('users', $newId);
        $this->assertNotNull($user);
        $this->assertEquals('Jane Doe', $user['name']);
    }

    public function testRead()
    {
        $user = $this->db->read('users', $this->lastInsertedIds[0]);
        $this->assertNotNull($user);
        $this->assertEquals('John Doe', $user['name']);
    }

    public function testUpdate()
    {
        $result = $this->db->update('users', $this->lastInsertedIds[0], ['name' => 'John Smith']);
        $this->assertTrue($result);
        $user = $this->db->read('users', $this->lastInsertedIds[0]);
        $this->assertEquals('John Smith', $user['name']);
    }

    public function testDelete()
    {
        $result = $this->db->delete('users', $this->lastInsertedIds[0]);
        $this->assertTrue($result);
        $user = $this->db->read('users', $this->lastInsertedIds[0]);
        $this->assertFalse($user); // PDO fetch() returns false if no rows are found
    }
}
