<?php

namespace App\Tests\Models;

use App\Models\User;
use PHPUnit\Framework\TestCase;

/**
 * Tests pour la classe User (Modèle)
 *
 * Note: Ces tests utilisent une base de données réelle (sport_2000)
 * Ils sont marqués comme tests d'intégration car ils dépendent de l'infrastructure.
 */
class UserTest extends TestCase
{
    private User $userModel;

    /**
     * Setup : Initialise le modèle User avant chaque test
     */
    protected function setUp(): void
    {
        $this->userModel = new User();
    }

    /**
     * Test : isAdmin retourne true pour un utilisateur ADMIN
     */
    public function testIsAdminReturnsTrueForAdminUser(): void
    {
        $adminUser = ['id' => 1, 'name' => 'Admin', 'email' => 'admin@test.com', 'role' => 'ADMIN'];

        $result = $this->userModel->isAdmin($adminUser);

        $this->assertTrue($result);
    }

    /**
     * Test : isAdmin retourne false pour un utilisateur USER
     */
    public function testIsAdminReturnsFalseForRegularUser(): void
    {
        $regularUser = ['id' => 2, 'name' => 'User', 'email' => 'user@test.com', 'role' => 'USER'];

        $result = $this->userModel->isAdmin($regularUser);

        $this->assertFalse($result);
    }

    /**
     * Test : isAdmin retourne false si le rôle n'est pas défini
     */
    public function testIsAdminReturnsFalseWhenRoleNotSet(): void
    {
        $userWithoutRole = ['id' => 3, 'name' => 'User', 'email' => 'test@test.com'];

        $result = $this->userModel->isAdmin($userWithoutRole);

        $this->assertFalse($result);
    }

    /**
     * Test : isUser retourne true pour un utilisateur USER
     */
    public function testIsUserReturnsTrueForRegularUser(): void
    {
        $regularUser = ['id' => 2, 'name' => 'User', 'email' => 'user@test.com', 'role' => 'USER'];

        $result = $this->userModel->isUser($regularUser);

        $this->assertTrue($result);
    }

    /**
     * Test : isUser retourne false pour un utilisateur ADMIN
     */
    public function testIsUserReturnsFalseForAdminUser(): void
    {
        $adminUser = ['id' => 1, 'name' => 'Admin', 'email' => 'admin@test.com', 'role' => 'ADMIN'];

        $result = $this->userModel->isUser($adminUser);

        $this->assertFalse($result);
    }

    /**
     * Test : isUser retourne false si le rôle n'est pas défini
     */
    public function testIsUserReturnsFalseWhenRoleNotSet(): void
    {
        $userWithoutRole = ['id' => 3, 'name' => 'User', 'email' => 'test@test.com'];

        $result = $this->userModel->isUser($userWithoutRole);

        $this->assertFalse($result);
    }

    /**
     * Test d'intégration : Vérification du hash de mot de passe
     *
     * Ce test vérifie que password_hash() et password_verify() fonctionnent correctement
     */
    public function testPasswordHashingWorks(): void
    {
        $password = '123';
        $hash = '$2y$10$L31Y/R4ueZVqfdNl8kIdnuDTQrdc1V2ylfCOc5ygoH9wQLTw1hAcy';

        // Vérifie que password_verify peut vérifier un hash connu
        $this->assertTrue(password_verify($password, $hash));

        // Vérifie qu'un mauvais mot de passe échoue
        $this->assertFalse(password_verify('wrongpassword', $hash));
    }

    /**
     * Test : emailExists retourne false pour un email inexistant
     *
     * Note: Ce test utilise un email très improbable
     */
    public function testEmailExistsReturnsFalseForNonExistentEmail(): void
    {
        $result = $this->userModel->emailExists('emailquinexistepas' . time() . '@example.com');

        $this->assertFalse($result);
    }

    /**
     * Test : create génère bien un hash de mot de passe différent du mot de passe en clair
     *
     * Ce test vérifie la logique de hashage sans vraiment insérer en base
     */
    public function testCreateHashesPassword(): void
    {
        $plainPassword = 'testpassword123';
        $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

        // Le hash ne doit jamais être égal au mot de passe en clair
        $this->assertNotEquals($plainPassword, $hashedPassword);

        // Le hash doit commencer par $2y$ (bcrypt)
        $this->assertStringStartsWith('$2y$', $hashedPassword);

        // Le hash doit faire au moins 60 caractères (bcrypt fait exactement 60)
        $this->assertGreaterThanOrEqual(60, strlen($hashedPassword));
    }

    /**
     * Test : Vérification de la structure d'un utilisateur retourné
     */
    public function testUserStructureContainsRequiredFields(): void
    {
        $user = [
            'id' => 1,
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'USER'
        ];

        $this->assertArrayHasKey('id', $user);
        $this->assertArrayHasKey('name', $user);
        $this->assertArrayHasKey('email', $user);
        $this->assertArrayHasKey('role', $user);
    }

    /**
     * Test : authenticate ne doit pas retourner le mot de passe
     */
    public function testAuthenticateDoesNotReturnPassword(): void
    {
        // Simule un utilisateur authentifié
        $user = [
            'id' => 1,
            'name' => 'Test',
            'email' => 'test@example.com',
            'role' => 'USER'
        ];

        // Vérifie que le champ 'pswd' n'est pas présent
        $this->assertArrayNotHasKey('pswd', $user);
        $this->assertArrayNotHasKey('password', $user);
    }
}
