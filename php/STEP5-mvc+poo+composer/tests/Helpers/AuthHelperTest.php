<?php

namespace App\Tests\Helpers;

use App\Helpers\AuthHelper;
use PHPUnit\Framework\TestCase;

/**
 * Tests pour la classe AuthHelper
 *
 * Ces tests vérifient la gestion de l'authentification et des autorisations
 */
class AuthHelperTest extends TestCase
{
    /**
     * Setup : Nettoie la session avant chaque test
     */
    protected function setUp(): void
    {
        // Démarre la session si ce n'est pas fait
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Nettoie complètement la session
        $_SESSION = [];
    }

    /**
     * Teardown : Nettoie après chaque test
     */
    protected function tearDown(): void
    {
        $_SESSION = [];
    }

    /**
     * Test : isLoggedIn retourne false quand personne n'est connecté
     */
    public function testIsLoggedInReturnsFalseWhenNotLoggedIn(): void
    {
        $result = AuthHelper::isLoggedIn();

        $this->assertFalse($result);
    }

    /**
     * Test : isLoggedIn retourne true quand un utilisateur est connecté
     */
    public function testIsLoggedInReturnsTrueWhenLoggedIn(): void
    {
        // Simule un utilisateur connecté
        $_SESSION['user'] = [
            'id' => 1,
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'USER'
        ];

        $result = AuthHelper::isLoggedIn();

        $this->assertTrue($result);
    }

    /**
     * Test : isAdmin retourne false quand personne n'est connecté
     */
    public function testIsAdminReturnsFalseWhenNotLoggedIn(): void
    {
        $result = AuthHelper::isAdmin();

        $this->assertFalse($result);
    }

    /**
     * Test : isAdmin retourne true pour un utilisateur ADMIN
     */
    public function testIsAdminReturnsTrueForAdminUser(): void
    {
        $_SESSION['user'] = [
            'id' => 1,
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'role' => 'ADMIN'
        ];

        $result = AuthHelper::isAdmin();

        $this->assertTrue($result);
    }

    /**
     * Test : isAdmin retourne false pour un utilisateur USER
     */
    public function testIsAdminReturnsFalseForRegularUser(): void
    {
        $_SESSION['user'] = [
            'id' => 2,
            'name' => 'User',
            'email' => 'user@example.com',
            'role' => 'USER'
        ];

        $result = AuthHelper::isAdmin();

        $this->assertFalse($result);
    }

    /**
     * Test : isUser retourne false quand personne n'est connecté
     */
    public function testIsUserReturnsFalseWhenNotLoggedIn(): void
    {
        $result = AuthHelper::isUser();

        $this->assertFalse($result);
    }

    /**
     * Test : isUser retourne true pour un utilisateur USER
     */
    public function testIsUserReturnsTrueForRegularUser(): void
    {
        $_SESSION['user'] = [
            'id' => 2,
            'name' => 'User',
            'email' => 'user@example.com',
            'role' => 'USER'
        ];

        $result = AuthHelper::isUser();

        $this->assertTrue($result);
    }

    /**
     * Test : isUser retourne false pour un utilisateur ADMIN
     */
    public function testIsUserReturnsFalseForAdminUser(): void
    {
        $_SESSION['user'] = [
            'id' => 1,
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'role' => 'ADMIN'
        ];

        $result = AuthHelper::isUser();

        $this->assertFalse($result);
    }

    /**
     * Test : getCurrentUser retourne null quand personne n'est connecté
     */
    public function testGetCurrentUserReturnsNullWhenNotLoggedIn(): void
    {
        $result = AuthHelper::getCurrentUser();

        $this->assertNull($result);
    }

    /**
     * Test : getCurrentUser retourne les données de l'utilisateur connecté
     */
    public function testGetCurrentUserReturnsUserData(): void
    {
        $userData = [
            'id' => 1,
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'USER'
        ];

        $_SESSION['user'] = $userData;

        $result = AuthHelper::getCurrentUser();

        $this->assertEquals($userData, $result);
    }

    /**
     * Test : getCurrentUserId retourne null quand personne n'est connecté
     */
    public function testGetCurrentUserIdReturnsNullWhenNotLoggedIn(): void
    {
        $result = AuthHelper::getCurrentUserId();

        $this->assertNull($result);
    }

    /**
     * Test : getCurrentUserId retourne l'ID de l'utilisateur
     */
    public function testGetCurrentUserIdReturnsUserId(): void
    {
        $_SESSION['user'] = [
            'id' => 42,
            'name' => 'Test',
            'email' => 'test@example.com',
            'role' => 'USER'
        ];

        $result = AuthHelper::getCurrentUserId();

        $this->assertEquals(42, $result);
        $this->assertIsInt($result);
    }

    /**
     * Test : getCurrentUserName retourne null quand personne n'est connecté
     */
    public function testGetCurrentUserNameReturnsNullWhenNotLoggedIn(): void
    {
        $result = AuthHelper::getCurrentUserName();

        $this->assertNull($result);
    }

    /**
     * Test : getCurrentUserName retourne le nom de l'utilisateur
     */
    public function testGetCurrentUserNameReturnsUserName(): void
    {
        $_SESSION['user'] = [
            'id' => 1,
            'name' => 'Jean Dupont',
            'email' => 'jean@example.com',
            'role' => 'USER'
        ];

        $result = AuthHelper::getCurrentUserName();

        $this->assertEquals('Jean Dupont', $result);
    }

    /**
     * Test : getCurrentUserRole retourne null quand personne n'est connecté
     */
    public function testGetCurrentUserRoleReturnsNullWhenNotLoggedIn(): void
    {
        $result = AuthHelper::getCurrentUserRole();

        $this->assertNull($result);
    }

    /**
     * Test : getCurrentUserRole retourne le rôle ADMIN
     */
    public function testGetCurrentUserRoleReturnsAdminRole(): void
    {
        $_SESSION['user'] = [
            'id' => 1,
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'role' => 'ADMIN'
        ];

        $result = AuthHelper::getCurrentUserRole();

        $this->assertEquals('ADMIN', $result);
    }

    /**
     * Test : getCurrentUserRole retourne le rôle USER
     */
    public function testGetCurrentUserRoleReturnsUserRole(): void
    {
        $_SESSION['user'] = [
            'id' => 2,
            'name' => 'User',
            'email' => 'user@example.com',
            'role' => 'USER'
        ];

        $result = AuthHelper::getCurrentUserRole();

        $this->assertEquals('USER', $result);
    }
}
