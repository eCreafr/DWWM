<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Database;

class DatabaseTest extends TestCase
{
    private $db;



    protected function setUp(): void
    {
        $this->db = new Database(getenv('DB_HOST'), getenv('DB_NAME'), getenv('DB_USER'), getenv('DB_PASS'));

        /* optionnel :
        // Nettoyer la table avant chaque test
        $this->db->query("TRUNCATE TABLE users"); 
        */

        // Insérer les données de test
        $this->db->create('users', ['id' => 2, 'name' => 'John Doe', 'email' => 'john@example.com']);
    }


    protected function tearDown(): void
    {
        if ($this->db) {
            $this->db->delete('users', 2); // l'utilisateur créé dans la plupart des tests
            $this->db->delete('users', 3); // l'utilisateur créé dans testCreate
        }
    }



    public function testDatabaseConnection()
    {
        $this->assertTrue($this->db->testConnection(), "La connexion à la base de données a échoué");
    }



    public function testCreate()
    {
        $result = $this->db->create('users', ['id' => 3, 'name' => 'Jane Doe', 'email' => 'jane@example.com']);
        $this->assertTrue($result);

        // Vérifier que l'utilisateur a bien été créé
        $user = $this->db->read('users', 3);
        $this->assertNotNull($user);
        $this->assertEquals('Jane Doe', $user['name']);
    }

    public function testRead()
    {
        $user = $this->db->read('users', 2);
        $this->assertNotNull($user);
        $this->assertEquals('John Doe', $user['name']);
    }

    public function testUpdate()
    {
        $result = $this->db->update('users', 2, ['name' => 'John Smith']);
        $this->assertTrue($result);
        $user = $this->db->read('users', 2);
        $this->assertEquals('John Smith', $user['name']);
    }

    public function testDelete()
    {
        $result = $this->db->delete('users', 2);
        $this->assertTrue($result);
        $user = $this->db->read('users', 2);
        $this->assertFalse($user); // PDO fetch() returns false if no rows are found
    }
}
