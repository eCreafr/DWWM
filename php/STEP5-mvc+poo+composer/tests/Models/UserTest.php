<?php

namespace App\Tests\Models;

use App\Models\User;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;

/**
 * Tests unitaires pour la classe User (Modele)
 *
 * Ces tests verifient la logique metier du modele User
 * SANS base de donnees reelle, grace a l'injection de dependance.
 *
 * Principe : on injecte un "mock" PDO (un faux objet qui simule PDO)
 * dans le constructeur du modele. Ainsi, on teste la logique PHP
 * du modele (hashage, verification de role, etc.) sans MySQL.
 */
class UserTest extends TestCase
{
    /**
     * Cree un modele User avec un mock PDO injecte
     *
     * Cette methode utilitaire evite de repeter la creation du mock
     * dans chaque test. Elle configure le mock PDO pour retourner
     * un PDOStatement qui peut etre personnalise par chaque test.
     *
     * @param PDOStatement|null $statement Le statement mock a retourner par prepare()
     * @return User Le modele User avec le mock injecte
     */
    private function createUserWithMockPdo(?PDOStatement $statement = null): User
    {
        // createMock() cree un faux objet PDO sans connexion reelle
        $pdo = $this->createMock(PDO::class);

        if ($statement !== null) {
            // Quand le code appelle $this->db->prepare(...), il recevra notre faux statement
            $pdo->method('prepare')->willReturn($statement);
        }

        // Injecte le faux PDO dans le modele User
        return new User($pdo);
    }

    /**
     * Cree un mock PDOStatement configure pour retourner des donnees
     *
     * @param mixed $fetchReturn Ce que fetch() doit retourner
     * @param mixed $fetchColumnReturn Ce que fetchColumn() doit retourner
     * @return PDOStatement
     */
    private function createMockStatement(mixed $fetchReturn = null, mixed $fetchColumnReturn = null): PDOStatement
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('execute')->willReturn(true);
        $stmt->method('bindValue')->willReturn(true);

        if ($fetchReturn !== null) {
            $stmt->method('fetch')->willReturn($fetchReturn);
        }

        if ($fetchColumnReturn !== null) {
            $stmt->method('fetchColumn')->willReturn($fetchColumnReturn);
        }

        return $stmt;
    }

    // ========================================
    // Tests de isAdmin() et isUser()
    // ========================================

    /**
     * Test : isAdmin retourne true pour un utilisateur ADMIN
     */
    public function testIsAdminReturnsTrueForAdminUser(): void
    {
        $user = $this->createUserWithMockPdo();

        $adminUser = ['id' => 1, 'name' => 'Admin', 'email' => 'admin@test.com', 'role' => 'ADMIN'];

        $this->assertTrue($user->isAdmin($adminUser));
    }

    /**
     * Test : isAdmin retourne false pour un utilisateur USER
     */
    public function testIsAdminReturnsFalseForRegularUser(): void
    {
        $user = $this->createUserWithMockPdo();

        $regularUser = ['id' => 2, 'name' => 'User', 'email' => 'user@test.com', 'role' => 'USER'];

        $this->assertFalse($user->isAdmin($regularUser));
    }

    /**
     * Test : isAdmin retourne false si le role n'est pas defini
     */
    public function testIsAdminReturnsFalseWhenRoleNotSet(): void
    {
        $user = $this->createUserWithMockPdo();

        $this->assertFalse($user->isAdmin(['id' => 3, 'name' => 'User']));
    }

    /**
     * Test : isUser retourne true pour un utilisateur USER
     */
    public function testIsUserReturnsTrueForRegularUser(): void
    {
        $user = $this->createUserWithMockPdo();

        $regularUser = ['id' => 2, 'name' => 'User', 'role' => 'USER'];

        $this->assertTrue($user->isUser($regularUser));
    }

    /**
     * Test : isUser retourne false pour un utilisateur ADMIN
     */
    public function testIsUserReturnsFalseForAdminUser(): void
    {
        $user = $this->createUserWithMockPdo();

        $this->assertFalse($user->isUser(['id' => 1, 'role' => 'ADMIN']));
    }

    // ========================================
    // Tests de authenticate()
    // ========================================

    /**
     * Test : authenticate retourne les donnees de l'utilisateur si le mot de passe est correct
     *
     * On simule une requete BDD qui retourne un utilisateur avec un mot de passe hashe.
     * La methode authenticate() doit verifier le hash et retourner l'utilisateur SANS le mot de passe.
     */
    public function testAuthenticateReturnsUserOnValidCredentials(): void
    {
        // Cree un hash reel pour le mot de passe "secret123"
        $hashedPassword = password_hash('secret123', PASSWORD_DEFAULT);

        // Configure le mock pour retourner un utilisateur avec son hash
        $stmt = $this->createMockStatement(fetchReturn: [
            'id' => 1,
            'name' => 'Jean Dupont',
            'email' => 'jean@example.com',
            'pswd' => $hashedPassword,
            'role' => 'USER',
            'two_factor_secret' => null,
            'two_factor_enabled' => 0,
        ]);

        $user = $this->createUserWithMockPdo($stmt);

        // Appelle la vraie methode authenticate()
        $result = $user->authenticate('jean@example.com', 'secret123');

        // Verifie que l'utilisateur est retourne
        $this->assertIsArray($result);
        $this->assertEquals(1, $result['id']);
        $this->assertEquals('Jean Dupont', $result['name']);
        $this->assertEquals('USER', $result['role']);

        // Verifie que le mot de passe a ete supprime du resultat (securite)
        $this->assertArrayNotHasKey('pswd', $result);
    }

    /**
     * Test : authenticate retourne false avec un mauvais mot de passe
     */
    public function testAuthenticateReturnsFalseOnWrongPassword(): void
    {
        $hashedPassword = password_hash('secret123', PASSWORD_DEFAULT);

        $stmt = $this->createMockStatement(fetchReturn: [
            'id' => 1,
            'name' => 'Jean',
            'email' => 'jean@example.com',
            'pswd' => $hashedPassword,
            'role' => 'USER',
            'two_factor_secret' => null,
            'two_factor_enabled' => 0,
        ]);

        $user = $this->createUserWithMockPdo($stmt);

        // Mot de passe incorrect
        $result = $user->authenticate('jean@example.com', 'mauvais_mdp');

        $this->assertFalse($result);
    }

    /**
     * Test : authenticate retourne false si l'email n'existe pas
     */
    public function testAuthenticateReturnsFalseForNonExistentEmail(): void
    {
        // Le mock retourne false (aucun utilisateur trouve)
        $stmt = $this->createMockStatement(fetchReturn: false);

        $user = $this->createUserWithMockPdo($stmt);

        $result = $user->authenticate('inconnu@example.com', 'password');

        $this->assertFalse($result);
    }

    // ========================================
    // Tests de emailExists()
    // ========================================

    /**
     * Test : emailExists retourne true quand l'email est en base
     */
    public function testEmailExistsReturnsTrueWhenEmailFound(): void
    {
        // fetchColumn retourne 1 (un utilisateur trouve)
        $stmt = $this->createMockStatement(fetchColumnReturn: 1);

        $user = $this->createUserWithMockPdo($stmt);

        $this->assertTrue($user->emailExists('jean@example.com'));
    }

    /**
     * Test : emailExists retourne false quand l'email n'est pas en base
     */
    public function testEmailExistsReturnsFalseWhenEmailNotFound(): void
    {
        // fetchColumn retourne 0 (aucun utilisateur)
        $stmt = $this->createMockStatement(fetchColumnReturn: 0);

        $user = $this->createUserWithMockPdo($stmt);

        $this->assertFalse($user->emailExists('inconnu@example.com'));
    }

    // ========================================
    // Tests de getById()
    // ========================================

    /**
     * Test : getById retourne les donnees de l'utilisateur
     */
    public function testGetByIdReturnsUserData(): void
    {
        $expectedUser = [
            'id' => 42,
            'name' => 'Marie Martin',
            'email' => 'marie@example.com',
            'role' => 'ADMIN',
        ];

        $stmt = $this->createMockStatement(fetchReturn: $expectedUser);

        $user = $this->createUserWithMockPdo($stmt);

        $result = $user->getById(42);

        $this->assertIsArray($result);
        $this->assertEquals(42, $result['id']);
        $this->assertEquals('Marie Martin', $result['name']);
    }

    /**
     * Test : getById retourne false si l'utilisateur n'existe pas
     */
    public function testGetByIdReturnsFalseWhenNotFound(): void
    {
        $stmt = $this->createMockStatement(fetchReturn: false);

        $user = $this->createUserWithMockPdo($stmt);

        $this->assertFalse($user->getById(999));
    }

    // ========================================
    // Tests de getAll()
    // ========================================

    /**
     * Test : getAll retourne un tableau d'utilisateurs
     */
    public function testGetAllReturnsArrayOfUsers(): void
    {
        $expectedUsers = [
            ['id' => 1, 'name' => 'Admin', 'email' => 'admin@test.com', 'role' => 'ADMIN'],
            ['id' => 2, 'name' => 'User', 'email' => 'user@test.com', 'role' => 'USER'],
        ];

        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('execute')->willReturn(true);
        $stmt->method('fetchAll')->willReturn($expectedUsers);

        $user = $this->createUserWithMockPdo($stmt);

        $result = $user->getAll();

        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        $this->assertEquals('Admin', $result[0]['name']);
    }

    // ========================================
    // Tests de create()
    // ========================================

    /**
     * Test : create hashe le mot de passe et retourne l'ID
     *
     * Ce test verifie que la methode create() :
     * 1. Appelle prepare() avec la bonne requete SQL
     * 2. Hashe le mot de passe (ne le stocke pas en clair)
     * 3. Retourne l'ID du nouvel utilisateur
     */
    public function testCreateHashesPasswordAndReturnsId(): void
    {
        // Capture les parametres passes a bindValue pour verifier le hash
        $boundValues = [];

        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('execute')->willReturn(true);
        $stmt->method('bindValue')->willReturnCallback(
            function (string $param, mixed $value) use (&$boundValues): bool {
                $boundValues[$param] = $value;
                return true;
            }
        );

        $pdo = $this->createMock(PDO::class);
        $pdo->method('prepare')->willReturn($stmt);
        $pdo->method('lastInsertId')->willReturn('7');

        $user = new User($pdo);

        $result = $user->create([
            'name' => 'Nouveau User',
            'email' => 'nouveau@example.com',
            'password' => 'monmotdepasse',
        ]);

        // Verifie que l'ID retourne est correct
        $this->assertEquals(7, $result);

        // Verifie que le mot de passe a ete hashe (pas stocke en clair)
        $this->assertNotEquals('monmotdepasse', $boundValues[':pswd']);

        // Verifie que le hash est valide (password_verify fonctionne)
        $this->assertTrue(password_verify('monmotdepasse', $boundValues[':pswd']));

        // Verifie que le role par defaut est USER
        $this->assertEquals('USER', $boundValues[':role']);
    }
}
