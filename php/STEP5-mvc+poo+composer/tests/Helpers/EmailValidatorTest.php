<?php

namespace App\Tests\Helpers;

use App\Helpers\EmailValidator;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * Tests pour la classe EmailValidator
 *
 * Ces tests vérifient la validation stricte des emails avec Egulias :
 * - Syntaxe RFC 5322
 * - Vérification DNS
 * - Vérification MX records
 */
class EmailValidatorTest extends TestCase
{
    /**
     * Test : Validation d'emails valides
     */
    #[DataProvider('validEmailsProvider')]
    public function testValidateWithValidEmails(string $email): void
    {
        $result = EmailValidator::validate($email);

        $this->assertTrue(
            $result,
            "L'email '{$email}' devrait être valide"
        );
    }

    /**
     * Test : Validation d'emails avec syntaxe invalide
     */
    #[DataProvider('invalidSyntaxEmailsProvider')]
    public function testValidateWithInvalidSyntax(string $email): void
    {
        $result = EmailValidator::validate($email);

        $this->assertFalse(
            $result,
            "L'email '{$email}' devrait être invalide (syntaxe)"
        );
    }

    /**
     * Test : Validation avec message pour email vide
     */
    public function testValidateWithMessageForEmptyEmail(): void
    {
        $result = EmailValidator::validateWithMessage('');

        $this->assertFalse($result['valid']);
        $this->assertStringContainsString('requis', $result['error']);
    }

    /**
     * Test : Validation avec message pour syntaxe invalide
     */
    public function testValidateWithMessageForInvalidSyntax(): void
    {
        $result = EmailValidator::validateWithMessage('invalid@');

        $this->assertFalse($result['valid']);
        $this->assertStringContainsString('format', strtolower($result['error']));
    }

    /**
     * Test : Validation avec message pour domaine inexistant
     */
    public function testValidateWithMessageForNonExistentDomain(): void
    {
        $result = EmailValidator::validateWithMessage('test@domainequinexistepas123456789.com');

        $this->assertFalse($result['valid']);
        $this->assertStringContainsString('domaine', strtolower($result['error']));
    }

    /**
     * Test : Validation avec message pour email valide
     */
    public function testValidateWithMessageForValidEmail(): void
    {
        $result = EmailValidator::validateWithMessage('test@gmail.com');

        $this->assertTrue($result['valid']);
        $this->assertNull($result['error']);
    }

    /**
     * Fournisseur de données : Emails valides
     */
    public static function validEmailsProvider(): array
    {
        return [
            'Gmail standard' => ['test@gmail.com'],
            'Outlook' => ['user@outlook.com'],
            'Yahoo' => ['contact@yahoo.com'],
            'Email avec point' => ['first.last@gmail.com'],
            'Email avec chiffres' => ['user123@gmail.com'],
        ];
    }

    /**
     * Fournisseur de données : Emails avec syntaxe invalide
     */
    public static function invalidSyntaxEmailsProvider(): array
    {
        return [
            'Sans @' => ['invalidemail'],
            'Double @' => ['test@@example.com'],
            'Sans domaine' => ['test@'],
            'Sans utilisateur' => ['@example.com'],
            'Espaces' => ['test @example.com'],
            'Caractères spéciaux' => ['test!#$%@example.com'],
        ];
    }
}
