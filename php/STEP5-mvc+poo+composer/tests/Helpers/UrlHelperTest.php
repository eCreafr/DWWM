<?php

namespace App\Tests\Helpers;

use App\Helpers\UrlHelper;
use PHPUnit\Framework\TestCase;

/**
 * Tests pour la classe UrlHelper
 *
 * Ces tests vérifient les fonctions de gestion d'URLs :
 * - Création d'URLs pour articles
 * - Génération d'URLs complètes
 * - Récupération de l'URL actuelle
 */
class UrlHelperTest extends TestCase
{
    /**
     * Setup : Définir BASE_URL pour les tests
     */
    protected function setUp(): void
    {
        // Définit BASE_URL si ce n'est pas déjà fait
        if (!defined('BASE_URL')) {
            define('BASE_URL', 'http://localhost/test');
        }
    }

    /**
     * Test : Création d'URL d'article sans score
     */
    public function testCreateArticleUrlWithoutScore(): void
    {
        $url = UrlHelper::createArticleUrl(123, 'Mon Super Article');

        $this->assertEquals('articles/123-mon-super-article.html', $url);
    }

    /**
     * Test : Création d'URL d'article avec score
     */
    public function testCreateArticleUrlWithScore(): void
    {
        $url = UrlHelper::createArticleUrl(456, 'Match de foot', '3-2');

        $this->assertEquals('articles/456-match-de-foot-3-2.html', $url);
    }

    /**
     * Test : Création d'URL avec titre accentué
     */
    public function testCreateArticleUrlWithAccents(): void
    {
        $url = UrlHelper::createArticleUrl(789, 'L\'Équipe de France gagne');

        $this->assertEquals('articles/789-l-equipe-de-france-gagne.html', $url);
    }

    /**
     * Test : Création d'URL avec caractères spéciaux
     */
    public function testCreateArticleUrlWithSpecialCharacters(): void
    {
        $url = UrlHelper::createArticleUrl(111, 'Article@#$%^&*()Test');

        // Le slugify garde un tiret entre "article" et "test"
        $this->assertEquals('articles/111-article-test.html', $url);
    }

    /**
     * Test : Génération d'URL complète
     */
    public function testUrlGeneratesCompleteUrl(): void
    {
        $url = UrlHelper::url('home.html');

        $this->assertEquals('http://localhost/test/home.html', $url);
    }

    /**
     * Test : Génération d'URL avec slash au début
     */
    public function testUrlWithLeadingSlash(): void
    {
        $url = UrlHelper::url('/articles.html');

        $this->assertEquals('http://localhost/test/articles.html', $url);
    }

    /**
     * Test : Génération d'URL vide
     */
    public function testUrlWithEmptyPath(): void
    {
        $url = UrlHelper::url('');

        $this->assertEquals('http://localhost/test/', $url);
    }

    /**
     * Test : getCurrentUrl avec HTTP
     */
    public function testGetCurrentUrlWithHTTP(): void
    {
        // Simule les variables serveur
        $_SERVER['HTTPS'] = 'off';
        $_SERVER['HTTP_HOST'] = 'example.com';
        $_SERVER['REQUEST_URI'] = '/page.html';

        $url = UrlHelper::getCurrentUrl();

        $this->assertEquals('http://example.com/page.html', $url);
    }

    /**
     * Test : getCurrentUrl avec HTTPS
     */
    public function testGetCurrentUrlWithHTTPS(): void
    {
        // Simule les variables serveur
        $_SERVER['HTTPS'] = 'on';
        $_SERVER['HTTP_HOST'] = 'example.com';
        $_SERVER['REQUEST_URI'] = '/secure.html';

        $url = UrlHelper::getCurrentUrl();

        $this->assertEquals('https://example.com/secure.html', $url);
    }

    /**
     * Test : getCurrentUrl sans HTTPS défini
     */
    public function testGetCurrentUrlWithoutHTTPS(): void
    {
        // Simule les variables serveur
        unset($_SERVER['HTTPS']);
        $_SERVER['HTTP_HOST'] = 'example.com';
        $_SERVER['REQUEST_URI'] = '/page.html';

        $url = UrlHelper::getCurrentUrl();

        $this->assertEquals('http://example.com/page.html', $url);
    }
}
