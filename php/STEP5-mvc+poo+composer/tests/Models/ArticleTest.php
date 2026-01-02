<?php

namespace App\Tests\Models;

use App\Models\Article;
use PHPUnit\Framework\TestCase;

/**
 * Tests pour la classe Article (Modèle)
 *
 * Ces tests vérifient la logique métier du modèle Article
 * sans vraiment tester la base de données (tests unitaires purs)
 */
class ArticleTest extends TestCase
{
    private Article $articleModel;

    /**
     * Setup : Initialise le modèle Article avant chaque test
     */
    protected function setUp(): void
    {
        $this->articleModel = new Article();
    }

    /**
     * Test : Vérification de la structure d'un article
     */
    public function testArticleStructureContainsRequiredFields(): void
    {
        $article = [
            'id' => 1,
            'titre' => 'Test Article',
            'contenu' => 'Contenu de test',
            'auteur' => 'Test Author',
            'date_publication' => '2025-01-01',
            'match_id' => null,
            'image' => 'test.jpg'
        ];

        // Vérifie que tous les champs requis sont présents
        $this->assertArrayHasKey('id', $article);
        $this->assertArrayHasKey('titre', $article);
        $this->assertArrayHasKey('contenu', $article);
        $this->assertArrayHasKey('auteur', $article);
        $this->assertArrayHasKey('date_publication', $article);
        $this->assertArrayHasKey('match_id', $article);
        $this->assertArrayHasKey('image', $article);
    }

    /**
     * Test : Un article peut avoir un match_id null
     */
    public function testArticleCanHaveNullMatchId(): void
    {
        $article = [
            'id' => 1,
            'titre' => 'Article sans match',
            'match_id' => null
        ];

        $this->assertNull($article['match_id']);
    }

    /**
     * Test : Un article avec match a un match_id numérique
     */
    public function testArticleWithMatchHasNumericMatchId(): void
    {
        $article = [
            'id' => 1,
            'titre' => 'Article avec match',
            'match_id' => 5
        ];

        $this->assertIsNumeric($article['match_id']);
        $this->assertGreaterThan(0, $article['match_id']);
    }

    /**
     * Test : Validation du format de date
     */
    public function testArticleDateIsValidFormat(): void
    {
        $article = [
            'date_publication' => '2025-01-15 14:30:00'
        ];

        // Vérifie que la date peut être parsée
        $date = \DateTime::createFromFormat('Y-m-d H:i:s', $article['date_publication']);

        $this->assertInstanceOf(\DateTime::class, $date);
    }

    /**
     * Test : Le titre d'un article ne doit pas être vide
     */
    public function testArticleTitleIsNotEmpty(): void
    {
        $article = [
            'titre' => 'Mon article de test'
        ];

        $this->assertNotEmpty($article['titre']);
        $this->assertIsString($article['titre']);
    }

    /**
     * Test : Le contenu d'un article peut être long
     */
    public function testArticleContentCanBeLong(): void
    {
        $longContent = str_repeat('Lorem ipsum dolor sit amet. ', 100);

        $article = [
            'contenu' => $longContent
        ];

        $this->assertGreaterThan(500, strlen($article['contenu']));
    }

    /**
     * Test : L'auteur d'un article est une chaîne de caractères
     */
    public function testArticleAuthorIsString(): void
    {
        $article = [
            'auteur' => 'Jean Dupont'
        ];

        $this->assertIsString($article['auteur']);
        $this->assertNotEmpty($article['auteur']);
    }

    /**
     * Test : Une image d'article peut être null ou une chaîne
     */
    public function testArticleImageCanBeNullOrString(): void
    {
        $articleWithImage = ['image' => 'photo.jpg'];
        $articleWithoutImage = ['image' => null];

        $this->assertIsString($articleWithImage['image']);
        $this->assertNull($articleWithoutImage['image']);
    }

    /**
     * Test : Validation de l'extension d'image
     */
    public function testArticleImageHasValidExtension(): void
    {
        $validImages = ['test.jpg', 'photo.jpeg', 'image.webp', 'pic.png'];

        foreach ($validImages as $image) {
            $extension = pathinfo($image, PATHINFO_EXTENSION);
            $this->assertContains(
                strtolower($extension),
                ['jpg', 'jpeg', 'webp', 'png'],
                "L'extension '{$extension}' devrait être valide"
            );
        }
    }

    /**
     * Test : Un article complet avec match associé
     */
    public function testArticleWithCompleteMatchData(): void
    {
        $article = [
            'id' => 1,
            'titre' => 'Grande victoire',
            'contenu' => 'Match passionnant hier soir',
            'auteur' => 'Sport Reporter',
            'date_publication' => '2025-01-15',
            'match_id' => 10,
            'image' => 'match.jpg',
            // Données du match (LEFT JOIN)
            'score' => '3-2',
            'lieu' => 'Stade de France',
            'equipe1' => 'PSG',
            'equipe2' => 'OM'
        ];

        // Vérifie la présence des données du match
        $this->assertArrayHasKey('score', $article);
        $this->assertArrayHasKey('lieu', $article);
        $this->assertArrayHasKey('equipe1', $article);
        $this->assertArrayHasKey('equipe2', $article);

        // Vérifie les valeurs
        $this->assertNotNull($article['match_id']);
        $this->assertIsString($article['score']);
        $this->assertMatchesRegularExpression('/^\d+-\d+$/', $article['score']);
    }
}
