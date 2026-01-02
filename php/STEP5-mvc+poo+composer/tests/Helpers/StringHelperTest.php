<?php

namespace App\Tests\Helpers;

use App\Helpers\StringHelper;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * Tests pour la classe StringHelper
 *
 * Ces tests vérifient les fonctions de manipulation de chaînes :
 * - Slugify (transformation en slug URL-friendly)
 * - Truncate (troncature de texte)
 * - Sanitize (nettoyage HTML)
 * - Escape (échappement HTML)
 */
class StringHelperTest extends TestCase
{
    /**
     * Test : Slugify avec différents textes
     */
    #[DataProvider('slugifyProvider')]
    public function testSlugify(string $input, string $expected): void
    {
        $result = StringHelper::slugify($input);

        $this->assertEquals(
            $expected,
            $result,
            "Le slug de '{$input}' devrait être '{$expected}'"
        );
    }

    /**
     * Test : Truncate avec chaîne courte (pas de troncature)
     */
    public function testTruncateShortString(): void
    {
        $result = StringHelper::truncate('Court', 20);

        $this->assertEquals('Court', $result);
    }

    /**
     * Test : Truncate avec chaîne longue
     */
    public function testTruncateLongString(): void
    {
        $result = StringHelper::truncate('Ceci est un texte très long', 10);

        $this->assertEquals('Ceci est u...', $result);
    }

    /**
     * Test : Truncate avec suffixe personnalisé
     */
    public function testTruncateCustomSuffix(): void
    {
        $result = StringHelper::truncate('Texte trop long', 10, ' [...]');

        $this->assertEquals('Texte trop [...]', $result);
    }

    /**
     * Test : Truncate avec caractères UTF-8
     */
    public function testTruncateWithUTF8(): void
    {
        $result = StringHelper::truncate('Café français', 8);

        $this->assertEquals('Café fra...', $result);
    }

    /**
     * Test : Sanitize supprime les balises HTML
     */
    public function testSanitizeRemovesHTMLTags(): void
    {
        $result = StringHelper::sanitize('<script>alert("XSS")</script>Texte');

        // strip_tags supprime les balises mais garde le contenu entre les balises
        $this->assertEquals('alert("XSS")Texte', $result);
    }

    /**
     * Test : Sanitize supprime les espaces
     */
    public function testSanitizeTrimsWhitespace(): void
    {
        $result = StringHelper::sanitize('   Texte avec espaces   ');

        $this->assertEquals('Texte avec espaces', $result);
    }

    /**
     * Test : Sanitize avec plusieurs balises
     */
    public function testSanitizeMultipleTags(): void
    {
        $result = StringHelper::sanitize('<b>Gras</b> et <i>italique</i>');

        $this->assertEquals('Gras et italique', $result);
    }

    /**
     * Test : Escape échappe les caractères HTML
     */
    public function testEscapeHTMLCharacters(): void
    {
        $result = StringHelper::escape('<script>alert("test")</script>');

        $this->assertStringContainsString('&lt;', $result);
        $this->assertStringContainsString('&gt;', $result);
    }

    /**
     * Test : Escape avec guillemets
     */
    public function testEscapeQuotes(): void
    {
        $result = StringHelper::escape('Text with "quotes" and \'apostrophes\'');

        $this->assertStringContainsString('&quot;', $result);
        $this->assertStringContainsString('&#039;', $result);
    }

    /**
     * Test : Escape ne modifie pas le texte normal
     */
    public function testEscapeNormalText(): void
    {
        $result = StringHelper::escape('Texte normal');

        $this->assertEquals('Texte normal', $result);
    }

    /**
     * Fournisseur de données pour testSlugify
     */
    public static function slugifyProvider(): array
    {
        return [
            'Texte simple' => ['Hello World', 'hello-world'],
            'Avec accents' => ['L\'Équipe de France', 'l-equipe-de-france'],
            'Avec chiffres' => ['Article 123', 'article-123'],
            'Espaces multiples' => ['Beaucoup    d\'espaces', 'beaucoup-d-espaces'],
            'Caractères spéciaux' => ['Test@#$%^&*()', 'test'],
            'Texte vide' => ['', 'n-a'],
            'Uniquement espaces' => ['   ', 'n-a'],
            'Majuscules' => ['TEXTE EN MAJUSCULES', 'texte-en-majuscules'],
        ];
    }
}
