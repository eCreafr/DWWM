<?php

namespace App\Tests;

use App\Router;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * Tests unitaires pour la classe Router
 *
 * Ces tests verifient la logique de routage de l'application :
 * - La whitelist des pages autorisees (securite)
 * - La resolution des routes (quelle page => quel controleur/action)
 * - Le rejet des pages invalides et malveillantes
 *
 * Grace aux methodes statiques getAllowedPages() et resolveRoute(),
 * on peut tester toute la logique de routing SANS instancier le Router
 * (qui aurait besoin des controleurs et donc de la base de donnees).
 */
class RouterTest extends TestCase
{
    // ========================================
    // Tests de getAllowedPages()
    // ========================================

    /**
     * Test : getAllowedPages retourne un tableau non vide
     */
    public function testGetAllowedPagesReturnsNonEmptyArray(): void
    {
        $pages = Router::getAllowedPages();

        $this->assertIsArray($pages);
        $this->assertNotEmpty($pages);
    }

    /**
     * Test : Les pages publiques essentielles sont autorisees
     */
    public function testAllowedPagesContainsPublicPages(): void
    {
        $pages = Router::getAllowedPages();

        $this->assertContains('home', $pages);
        $this->assertContains('articles', $pages);
        $this->assertContains('login', $pages);
        $this->assertContains('register', $pages);
    }

    /**
     * Test : Les pages CRUD articles sont autorisees
     */
    public function testAllowedPagesContainsCrudPages(): void
    {
        $pages = Router::getAllowedPages();

        $this->assertContains('add', $pages);
        $this->assertContains('addpost', $pages);
        $this->assertContains('edit', $pages);
        $this->assertContains('editpost', $pages);
        $this->assertContains('deletepost', $pages);
    }

    /**
     * Test : Les pages 2FA sont autorisees
     */
    public function testAllowedPagesContains2FAPages(): void
    {
        $pages = Router::getAllowedPages();

        $this->assertContains('verify-2fa', $pages);
        $this->assertContains('2fa-settings', $pages);
        $this->assertContains('enable-2fa', $pages);
        $this->assertContains('disable-2fa', $pages);
    }

    // ========================================
    // Tests de resolveRoute() - Routes valides
    // ========================================

    /**
     * Test : La page home route vers le controleur article/index
     */
    public function testResolveRouteHome(): void
    {
        $route = Router::resolveRoute('home');

        $this->assertNotNull($route);
        $this->assertEquals('article', $route['controller']);
        $this->assertEquals('index', $route['action']);
    }

    /**
     * Test : La page login route vers auth/showLoginForm
     */
    public function testResolveRouteLogin(): void
    {
        $route = Router::resolveRoute('login');

        $this->assertNotNull($route);
        $this->assertEquals('auth', $route['controller']);
        $this->assertEquals('showLoginForm', $route['action']);
    }

    /**
     * Test : La page register route vers register/showRegisterForm
     */
    public function testResolveRouteRegister(): void
    {
        $route = Router::resolveRoute('register');

        $this->assertNotNull($route);
        $this->assertEquals('register', $route['controller']);
        $this->assertEquals('showRegisterForm', $route['action']);
    }

    /**
     * Test : La page tests route vers test/index
     */
    public function testResolveRouteTests(): void
    {
        $route = Router::resolveRoute('tests');

        $this->assertNotNull($route);
        $this->assertEquals('test', $route['controller']);
        $this->assertEquals('index', $route['action']);
    }

    /**
     * Test : Les pages POST routent vers les bonnes actions
     */
    #[DataProvider('postRoutesProvider')]
    public function testResolveRoutePostActions(string $page, string $expectedController, string $expectedAction): void
    {
        $route = Router::resolveRoute($page);

        $this->assertNotNull($route, "La route '{$page}' ne devrait pas etre null");
        $this->assertEquals($expectedController, $route['controller']);
        $this->assertEquals($expectedAction, $route['action']);
    }

    // ========================================
    // Tests de resolveRoute() - Article detail
    // ========================================

    /**
     * Test : articles avec un ID numerique route vers article/show
     */
    public function testResolveRouteArticleDetailWithId(): void
    {
        $route = Router::resolveRoute('articles', ['id' => '123']);

        $this->assertNotNull($route);
        $this->assertEquals('article', $route['controller']);
        $this->assertEquals('show', $route['action']);
        $this->assertEquals(123, $route['params']['id']);
    }

    /**
     * Test : articles sans ID route vers article/index (liste)
     */
    public function testResolveRouteArticlesWithoutIdShowsList(): void
    {
        $route = Router::resolveRoute('articles');

        $this->assertNotNull($route);
        $this->assertEquals('article', $route['controller']);
        $this->assertEquals('index', $route['action']);
    }

    /**
     * Test : L'ID d'article est converti en entier
     */
    public function testResolveRouteArticleIdIsCastToInt(): void
    {
        $route = Router::resolveRoute('articles', ['id' => '456']);

        $this->assertIsInt($route['params']['id']);
        $this->assertEquals(456, $route['params']['id']);
    }

    // ========================================
    // Tests de resolveRoute() - Page edit avec ID
    // ========================================

    /**
     * Test : edit avec un ID valide route correctement
     */
    public function testResolveRouteEditWithValidId(): void
    {
        $route = Router::resolveRoute('edit', ['id' => '42']);

        $this->assertNotNull($route);
        $this->assertEquals('article', $route['controller']);
        $this->assertEquals('edit', $route['action']);
        $this->assertEquals(42, $route['params']['id']);
    }

    /**
     * Test : edit sans ID retourne null (404)
     */
    public function testResolveRouteEditWithoutIdReturnsNull(): void
    {
        $route = Router::resolveRoute('edit');

        $this->assertNull($route);
    }

    /**
     * Test : edit avec un ID non numerique retourne null (404)
     */
    public function testResolveRouteEditWithInvalidIdReturnsNull(): void
    {
        $route = Router::resolveRoute('edit', ['id' => 'abc']);

        $this->assertNull($route);
    }

    // ========================================
    // Tests de resolveRoute() - Pages invalides
    // ========================================

    /**
     * Test : Une page inexistante retourne null
     */
    public function testResolveRouteReturnsNullForUnknownPage(): void
    {
        $this->assertNull(Router::resolveRoute('pagequinexistepas'));
    }

    /**
     * Test : Securite - Les tentatives de path traversal sont rejetees
     */
    #[DataProvider('maliciousPagesProvider')]
    public function testResolveRouteRejectsMaliciousPages(string $maliciousPage): void
    {
        $result = Router::resolveRoute($maliciousPage);

        $this->assertNull(
            $result,
            "La page malveillante '{$maliciousPage}' aurait du etre rejetee"
        );
    }

    /**
     * Test : Securite - L'injection SQL dans l'ID d'article est rejetee
     */
    public function testResolveRouteRejectsSQLInjectionInId(): void
    {
        // L'ID non numerique ne declenche pas la route article/show
        $route = Router::resolveRoute('articles', ['id' => "1' OR '1'='1"]);

        // Doit retourner la liste des articles, pas un article specifique
        $this->assertNotNull($route);
        $this->assertEquals('index', $route['action']);
    }

    // ========================================
    // Data Providers
    // ========================================

    /**
     * Fournisseur de donnees : routes POST et leurs actions attendues
     */
    public static function postRoutesProvider(): array
    {
        return [
            'addpost'      => ['addpost',      'article',  'store'],
            'editpost'     => ['editpost',     'article',  'update'],
            'deletepost'   => ['deletepost',   'article',  'delete'],
            'loginpost'    => ['loginpost',    'auth',     'login'],
            'registerpost' => ['registerpost', 'register', 'register'],
            'logout'       => ['logout',       'auth',     'logout'],
        ];
    }

    /**
     * Fournisseur de donnees : pages malveillantes (path traversal, etc.)
     */
    public static function maliciousPagesProvider(): array
    {
        return [
            'Path traversal Unix'     => ['../config/database'],
            'Path traversal profond'  => ['../../etc/passwd'],
            'Path traversal relatif'  => ['config/../database'],
            'Path traversal Windows'  => ['..\\..\\windows\\system32'],
            'Page admin inexistante'  => ['admin'],
            'Page config'             => ['config'],
            'Page database'           => ['database'],
            'Chaine vide'             => [''],
        ];
    }
}
