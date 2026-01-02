<?php

namespace App\Helpers;

use Egulias\EmailValidator\EmailValidator as EguliasValidator;
use Egulias\EmailValidator\Validation\DNSCheckValidation;
use Egulias\EmailValidator\Validation\MultipleValidationWithAnd;
use Egulias\EmailValidator\Validation\RFCValidation;

/**
 * Classe EmailValidator - Helper pour la validation stricte des emails
 *
 * Utilise la bibliothèque Egulias Email Validator pour vérifier :
 * - La syntaxe RFC (format standard des emails)
 * - L'existence du domaine (DNS)
 * - La présence d'enregistrements MX (serveurs de messagerie)
 */
class EmailValidator
{
    /**
     * Valide un email avec vérification stricte (RFC + DNS + MX records)
     *
     * @param string $email L'adresse email à valider
     * @return bool true si l'email est valide, false sinon
     */
    public static function validate(string $email): bool
    {
        // Crée une instance du validateur Egulias
        $validator = new EguliasValidator();

        // Combine plusieurs validations :
        // 1. RFCValidation : Vérifie que l'email respecte la RFC 5322 (syntaxe)
        // 2. DNSCheckValidation : Vérifie que le domaine existe et a des MX records
        $multipleValidations = new MultipleValidationWithAnd([
            new RFCValidation(),
            new DNSCheckValidation()
        ]);

        // Retourne true si toutes les validations passent
        return $validator->isValid($email, $multipleValidations);
    }

    /**
     * Valide un email et retourne un message d'erreur si invalide
     *
     * @param string $email L'adresse email à valider
     * @return array ['valid' => bool, 'error' => string|null]
     */
    public static function validateWithMessage(string $email): array
    {
        // Vérifie d'abord que l'email n'est pas vide
        if (empty($email)) {
            return [
                'valid' => false,
                'error' => 'L\'adresse email est requise.'
            ];
        }

        // Crée une instance du validateur
        $validator = new EguliasValidator();

        // Validation en deux étapes pour des messages d'erreur précis

        // Étape 1 : Vérification de la syntaxe RFC
        $rfcValidation = new RFCValidation();
        if (!$validator->isValid($email, $rfcValidation)) {
            return [
                'valid' => false,
                'error' => 'Le format de l\'adresse email est invalide.'
            ];
        }

        // Étape 2 : Vérification DNS + MX records
        $dnsValidation = new DNSCheckValidation();
        if (!$validator->isValid($email, $dnsValidation)) {
            return [
                'valid' => false,
                'error' => 'Le domaine de l\'adresse email n\'existe pas ou ne peut pas recevoir d\'emails.'
            ];
        }

        // Si toutes les validations passent
        return [
            'valid' => true,
            'error' => null
        ];
    }
}
