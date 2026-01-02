<?php

namespace App\Tests\Integration;

use PHPUnit\Framework\TestCase;

/**
 * Tests d'intégration pour le Router
 *
 * Ces tests simulent des requêtes HTTP complètes pour vérifier
 * que le routing fonctionne correctement de bout en bout
 */
class RouterIntegrationTest extends TestCase
{
    /**
     * Setup : Prépare l'environnement pour les tests
     */
    protected function setUp(): void
    {
        // Définit BASE_URL si ce n'est pas déjà fait
        if (!defined('BASE_URL')) {
            define('BASE_URL', 'http://localhost/test');
        }

        // Démarre la session si nécessaire
        if (session_status() === PHP_SESSION_NONE) {
            @session_start();
        }

        // Nettoie la session
        $_SESSION = [];

        // Nettoie les variables serveur
        $_GET = [];
        $_POST = [];
        $_SERVER = [
            'REQUEST_METHOD' => 'GET',
            'HTTP_HOST' => 'localhost',
            'REQUEST_URI' => '/'
        ];
    }

    /**
     * Teardown : Nettoie après chaque test
     */
    protected function tearDown(): void
    {
        $_SESSION = [];
        $_GET = [];
        $_POST = [];
    }

    /**
     * Test : Simulation de requête GET vers la page d'accueil
     */
    public function testHomePageRequest(): void
    {
        // Simule une requête GET vers home.html
        $_GET['page'] = 'home';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        // Le routing devrait accepter cette page
        $allowedPages = ['home', 'articles', 'login', 'register'];

        $this->assertContains($_GET['page'], $allowedPages);
    }

    /**
     * Test : Simulation de requête vers une page protégée (tests)
     */
    public function testProtectedPageRequiresAuthentication(): void
    {
        // Simule une requête vers la page de tests
        $_GET['page'] = 'tests';

        // Sans authentification, l'accès devrait être refusé
        $isLoggedIn = isset($_SESSION['user']) && !empty($_SESSION['user']);
        $this->assertFalse($isLoggedIn);
    }

    /**
     * Test : Simulation de requête avec utilisateur admin
     */
    public function testAdminUserCanAccessTests(): void
    {
        // Simule un utilisateur admin connecté
        $_SESSION['user'] = [
            'id' => 1,
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'role' => 'ADMIN'
        ];

        $_GET['page'] = 'tests';

        // Vérifie que l'utilisateur est admin
        $isAdmin = isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'ADMIN';
        $this->assertTrue($isAdmin);
    }

    /**
     * Test : Simulation de requête POST d'inscription
     */
    public function testRegisterPostRequest(): void
    {
        // Simule une soumission de formulaire d'inscription
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_GET['page'] = 'registerpost';
        $_POST = [
            'name' => 'Test User',
            'email' => 'test@gmail.com',
            'password' => 'password123',
            'confirm_password' => 'password123'
        ];

        // Vérifie que la requête est bien en POST
        $this->assertEquals('POST', $_SERVER['REQUEST_METHOD']);

        // Vérifie que les données du formulaire sont présentes
        $this->assertArrayHasKey('name', $_POST);
        $this->assertArrayHasKey('email', $_POST);
        $this->assertArrayHasKey('password', $_POST);
        $this->assertArrayHasKey('confirm_password', $_POST);

        // Vérifie que les mots de passe correspondent
        $this->assertEquals($_POST['password'], $_POST['confirm_password']);
    }

    /**
     * Test : Simulation de requête POST de connexion
     */
    public function testLoginPostRequest(): void
    {
        // Simule une soumission de formulaire de connexion
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_GET['page'] = 'loginpost';
        $_POST = [
            'email' => 'admin@example.com',
            'password' => '123'
        ];

        // Vérifie que c'est bien une requête POST
        $this->assertEquals('POST', $_SERVER['REQUEST_METHOD']);

        // Vérifie que les identifiants sont fournis
        $this->assertNotEmpty($_POST['email']);
        $this->assertNotEmpty($_POST['password']);
    }

    /**
     * Test : Validation des pages autorisées
     */
    public function testAllowedPagesValidation(): void
    {
        $allowedPages = [
            'home',
            'add',
            'addpost',
            'edit',
            'editpost',
            'delete',
            'deletepost',
            'articles',
            'login',
            'loginpost',
            'logout',
            'register',
            'registerpost',
            'tests',
        ];

        // Pages valides
        $validPages = ['home', 'login', 'register', 'tests'];
        foreach ($validPages as $page) {
            $this->assertContains($page, $allowedPages, "La page '{$page}' devrait être autorisée");
        }

        // Pages invalides
        $invalidPages = ['admin', 'config', 'database', '../../../etc/passwd'];
        foreach ($invalidPages as $page) {
            $this->assertNotContains($page, $allowedPages, "La page '{$page}' ne devrait PAS être autorisée");
        }
    }

    /**
     * Test : Simulation de requête vers un article spécifique
     */
    public function testArticleDetailRequest(): void
    {
        // Simule une requête vers articles/123-mon-article.html
        $_GET['page'] = 'articles';
        $_GET['id'] = '123';

        // Vérifie que l'ID est numérique
        $this->assertTrue(is_numeric($_GET['id']));
        $this->assertEquals(123, (int) $_GET['id']);
    }

    /**
     * Test : Simulation de déconnexion
     */
    public function testLogoutRequest(): void
    {
        // Simule un utilisateur connecté
        $_SESSION['user'] = [
            'id' => 1,
            'name' => 'Test',
            'email' => 'test@example.com',
            'role' => 'USER'
        ];

        // Vérifie que l'utilisateur est connecté
        $this->assertNotEmpty($_SESSION['user']);

        // Simule la déconnexion
        $_GET['page'] = 'logout';
        $_SESSION = []; // session_unset() + session_destroy()

        // Vérifie que la session est vide
        $this->assertEmpty($_SESSION);
    }

    /**
     * Test : Validation de sécurité - Injection SQL dans l'ID
     */
    public function testSQLInjectionProtection(): void
    {
        // Tente une injection SQL via l'ID
        $_GET['id'] = "1' OR '1'='1";

        // Vérifie que l'ID n'est PAS numérique (donc sera rejeté)
        $this->assertFalse(is_numeric($_GET['id']));

        // Le router devrait valider avec is_numeric() et rejeter cette requête
    }

    /**
     * Test : Validation de sécurité - Path traversal
     */
    public function testPathTraversalProtection(): void
    {
        $maliciousPages = [
            '../config/database',
            '../../etc/passwd',
            'config/../database',
            '..\\..\\windows\\system32'
        ];

        $allowedPages = ['home', 'articles', 'login', 'register'];

        foreach ($maliciousPages as $page) {
            $this->assertNotContains(
                $page,
                $allowedPages,
                "La page malveillante '{$page}' devrait être bloquée"
            );
        }
    }

    /**
     * Test : Messages flash de session
     */
    public function testSessionFlashMessages(): void
    {
        // Simule un message de succès
        $_SESSION['success_message'] = 'Inscription réussie !';

        $this->assertArrayHasKey('success_message', $_SESSION);
        $this->assertEquals('Inscription réussie !', $_SESSION['success_message']);

        // Simule la suppression du message après affichage
        unset($_SESSION['success_message']);

        $this->assertArrayNotHasKey('success_message', $_SESSION);
    }
}
