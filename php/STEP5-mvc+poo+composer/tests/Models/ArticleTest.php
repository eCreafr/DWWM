<?php

namespace App\Tests\Models;

use App\Models\Article;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;

/**
 * Tests unitaires pour la classe Article (Modele)
 *
 * Ces tests verifient les methodes CRUD du modele Article
 * SANS base de donnees reelle, grace a l'injection d'un mock PDO.
 *
 * Chaque test suit le pattern Arrange-Act-Assert :
 * 1. Arrange : on configure le mock PDO pour simuler la BDD
 * 2. Act : on appelle la vraie methode du modele
 * 3. Assert : on verifie le resultat
 */
class ArticleTest extends TestCase
{
    /**
     * Cree un modele Article avec un mock PDO injecte
     *
     * @param PDOStatement|null $statement Le statement mock a retourner
     * @param PDO|null $pdo Un mock PDO personnalise (si besoin de configurer lastInsertId, etc.)
     * @return Article
     */
    private function createArticleWithMockPdo(?PDOStatement $statement = null, ?PDO $pdo = null): Article
    {
        if ($pdo === null) {
            $pdo = $this->createMock(PDO::class);

            if ($statement !== null) {
                $pdo->method('prepare')->willReturn($statement);
            }
        }

        return new Article($pdo);
    }

    /**
     * Cree un mock PDOStatement configure
     *
     * @param mixed $fetchReturn Ce que fetch() retourne
     * @param mixed $fetchAllReturn Ce que fetchAll() retourne
     * @return PDOStatement
     */
    private function createMockStatement(mixed $fetchReturn = null, mixed $fetchAllReturn = null): PDOStatement
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('execute')->willReturn(true);
        $stmt->method('bindParam')->willReturn(true);

        if ($fetchReturn !== null) {
            $stmt->method('fetch')->willReturn($fetchReturn);
        }

        if ($fetchAllReturn !== null) {
            $stmt->method('fetchAll')->willReturn($fetchAllReturn);
        }

        return $stmt;
    }

    // ========================================
    // Tests de getAll()
    // ========================================

    /**
     * Test : getAll retourne tous les articles avec leurs matchs associes
     */
    public function testGetAllReturnsArrayOfArticles(): void
    {
        $expectedArticles = [
            [
                'id' => 1,
                'titre' => 'Grande victoire',
                'contenu' => 'Match passionnant',
                'auteur' => 'Reporter',
                'date_publication' => '2025-01-15',
                'match_id' => 10,
                'image' => 'match.jpg',
                'score' => '3-2',
                'lieu' => 'Stade de France',
                'equipe1' => 'PSG',
                'equipe2' => 'OM',
            ],
            [
                'id' => 2,
                'titre' => 'Article sans match',
                'contenu' => 'Contenu divers',
                'auteur' => 'Journaliste',
                'date_publication' => '2025-01-16',
                'match_id' => null,
                'image' => null,
                'score' => null,
                'lieu' => null,
                'equipe1' => null,
                'equipe2' => null,
            ],
        ];

        $stmt = $this->createMockStatement(fetchAllReturn: $expectedArticles);

        $article = $this->createArticleWithMockPdo($stmt);

        $result = $article->getAll();

        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        $this->assertEquals('Grande victoire', $result[0]['titre']);
        $this->assertEquals('3-2', $result[0]['score']);
        // L'article sans match a des valeurs null pour les champs du match
        $this->assertNull($result[1]['match_id']);
        $this->assertNull($result[1]['score']);
    }

    /**
     * Test : getAll retourne un tableau vide s'il n'y a aucun article
     */
    public function testGetAllReturnsEmptyArrayWhenNoArticles(): void
    {
        $stmt = $this->createMockStatement(fetchAllReturn: []);

        $article = $this->createArticleWithMockPdo($stmt);

        $result = $article->getAll();

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    // ========================================
    // Tests de getById()
    // ========================================

    /**
     * Test : getById retourne un article avec ses donnees de match
     */
    public function testGetByIdReturnsArticleData(): void
    {
        $expectedArticle = [
            'id' => 1,
            'titre' => 'Titre de test',
            'contenu' => 'Contenu de test',
            'auteur' => 'Auteur',
            'date_publication' => '2025-01-15',
            'match_id' => 5,
            'image' => 'photo.jpg',
            'score' => '2-1',
            'lieu' => 'Parc des Princes',
            'equipe1' => 'PSG',
            'equipe2' => 'Lyon',
            'resume' => 'Resume du match',
        ];

        $stmt = $this->createMockStatement(fetchReturn: $expectedArticle);

        $article = $this->createArticleWithMockPdo($stmt);

        $result = $article->getById(1);

        $this->assertIsArray($result);
        $this->assertEquals(1, $result['id']);
        $this->assertEquals('Titre de test', $result['titre']);
        $this->assertEquals('2-1', $result['score']);
    }

    /**
     * Test : getById retourne false si l'article n'existe pas
     */
    public function testGetByIdReturnsFalseWhenNotFound(): void
    {
        $stmt = $this->createMockStatement(fetchReturn: false);

        $article = $this->createArticleWithMockPdo($stmt);

        $this->assertFalse($article->getById(999));
    }

    // ========================================
    // Tests de create()
    // ========================================

    /**
     * Test : create insere un article et retourne son ID
     */
    public function testCreateReturnsNewArticleId(): void
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('execute')->willReturn(true);

        $pdo = $this->createMock(PDO::class);
        $pdo->method('prepare')->willReturn($stmt);
        $pdo->method('lastInsertId')->willReturn('42');

        $article = new Article($pdo);

        $result = $article->create([
            'titre' => 'Nouvel article',
            'contenu' => 'Le contenu de mon article',
            'auteur' => 'Jean Dupont',
            'match_id' => 5,
            'image' => 'photo.jpg',
        ]);

        $this->assertEquals(42, $result);
    }

    /**
     * Test : create utilise match_id = 0 par defaut si non fourni
     */
    public function testCreateUsesDefaultMatchIdWhenNotProvided(): void
    {
        $executedParams = [];

        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('execute')->willReturnCallback(
            function (array $params) use (&$executedParams): bool {
                $executedParams = $params;
                return true;
            }
        );

        $pdo = $this->createMock(PDO::class);
        $pdo->method('prepare')->willReturn($stmt);
        $pdo->method('lastInsertId')->willReturn('1');

        $article = new Article($pdo);

        $article->create([
            'titre' => 'Article sans match',
            'contenu' => 'Contenu',
            'auteur' => 'Auteur',
        ]);

        // match_id doit etre 0 par defaut (pas null)
        $this->assertEquals(0, $executedParams['match_id']);
        // image doit etre null par defaut
        $this->assertNull($executedParams['image']);
    }

    // ========================================
    // Tests de update()
    // ========================================

    /**
     * Test : update retourne true en cas de succes (sans image)
     */
    public function testUpdateWithoutImageReturnsTrue(): void
    {
        $executedParams = [];

        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('execute')->willReturnCallback(
            function (array $params) use (&$executedParams): bool {
                $executedParams = $params;
                return true;
            }
        );

        $pdo = $this->createMock(PDO::class);
        $pdo->method('prepare')->willReturn($stmt);

        $article = new Article($pdo);

        $result = $article->update(1, [
            'titre' => 'Titre modifie',
            'contenu' => 'Contenu modifie',
        ]);

        $this->assertTrue($result);
        // Verifie que les bons parametres sont passes
        $this->assertEquals('Titre modifie', $executedParams['titre']);
        $this->assertEquals('Contenu modifie', $executedParams['contenu']);
        $this->assertEquals(1, $executedParams['id']);
        // Pas de cle 'image' dans les parametres
        $this->assertArrayNotHasKey('image', $executedParams);
    }

    /**
     * Test : update avec image inclut le champ image dans la requete
     */
    public function testUpdateWithImageIncludesImageField(): void
    {
        $executedParams = [];

        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('execute')->willReturnCallback(
            function (array $params) use (&$executedParams): bool {
                $executedParams = $params;
                return true;
            }
        );

        $pdo = $this->createMock(PDO::class);
        $pdo->method('prepare')->willReturn($stmt);

        $article = new Article($pdo);

        $result = $article->update(1, [
            'titre' => 'Titre',
            'contenu' => 'Contenu',
            'image' => 'nouvelle-photo.jpg',
        ]);

        $this->assertTrue($result);
        // Verifie que l'image est incluse dans les parametres
        $this->assertEquals('nouvelle-photo.jpg', $executedParams['image']);
    }

    // ========================================
    // Tests de delete()
    // ========================================

    /**
     * Test : delete retourne true en cas de succes
     */
    public function testDeleteReturnsTrue(): void
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('execute')->willReturn(true);

        $article = $this->createArticleWithMockPdo($stmt);

        $this->assertTrue($article->delete(1));
    }

    /**
     * Test : delete retourne false en cas d'echec
     */
    public function testDeleteReturnsFalseOnFailure(): void
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('execute')->willReturn(false);

        $article = $this->createArticleWithMockPdo($stmt);

        $this->assertFalse($article->delete(999));
    }

    // ========================================
    // Tests de getMatchId()
    // ========================================

    /**
     * Test : getMatchId retourne l'ID du match associe
     */
    public function testGetMatchIdReturnsMatchId(): void
    {
        $stmt = $this->createMockStatement(fetchReturn: ['match_id' => 10]);

        $article = $this->createArticleWithMockPdo($stmt);

        $this->assertEquals(10, $article->getMatchId(1));
    }

    /**
     * Test : getMatchId retourne null si pas de match associe
     */
    public function testGetMatchIdReturnsNullWhenNoMatch(): void
    {
        $stmt = $this->createMockStatement(fetchReturn: ['match_id' => 0]);

        $article = $this->createArticleWithMockPdo($stmt);

        $this->assertNull($article->getMatchId(1));
    }

    /**
     * Test : getMatchId retourne null si l'article n'existe pas
     */
    public function testGetMatchIdReturnsNullWhenArticleNotFound(): void
    {
        $stmt = $this->createMockStatement(fetchReturn: false);

        $article = $this->createArticleWithMockPdo($stmt);

        $this->assertNull($article->getMatchId(999));
    }
}
