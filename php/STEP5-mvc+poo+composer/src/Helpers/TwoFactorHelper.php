<?php

namespace App\Helpers;

use PragmaRX\Google2FA\Google2FA;

/**
 * Classe TwoFactorHelper - Gestion de l'authentification à deux facteurs (2FA)
 *
 * Cette classe utilise la bibliothèque PragmaRX Google2FA pour gérer l'authentification
 * à deux facteurs compatible avec Google Authenticator, Authy, etc.
 *
 * Fonctionnalités :
 * - Génération de secrets 2FA
 * - Génération de QR codes pour l'activation
 * - Vérification des codes OTP (One-Time Password)
 */
class TwoFactorHelper
{
    /**
     * Instance de Google2FA
     * @var Google2FA
     */
    private static ?Google2FA $google2fa = null;

    /**
     * Récupère l'instance de Google2FA (singleton)
     *
     * @return Google2FA
     */
    private static function getGoogle2FA(): Google2FA
    {
        if (self::$google2fa === null) {
            self::$google2fa = new Google2FA();
        }
        return self::$google2fa;
    }

    /**
     * Génère un nouveau secret 2FA
     *
     * @return string Le secret encodé en base32
     */
    public static function generateSecret(): string
    {
        return self::getGoogle2FA()->generateSecretKey();
    }

    /**
     * Génère l'URL du QR code pour Google Authenticator
     *
     * @param string $email Email de l'utilisateur
     * @param string $secret Secret 2FA
     * @param string $appName Nom de l'application (par défaut: "Sport 2000")
     * @return string URL du QR code
     */
    public static function getQRCodeUrl(string $email, string $secret, string $appName = 'Sport 2000'): string
    {
        return self::getGoogle2FA()->getQRCodeUrl(
            $appName,
            $email,
            $secret
        );
    }

    /**
     * Génère l'image QR code en format SVG
     *
     * @param string $email Email de l'utilisateur
     * @param string $secret Secret 2FA
     * @param string $appName Nom de l'application
     * @return string SVG du QR code (inline)
     */
    public static function getQRCodeInline(string $email, string $secret, string $appName = 'Sport 2000'): string
    {
        $qrCodeUrl = self::getQRCodeUrl($email, $secret, $appName);

        // Génère un QR code inline en utilisant une API externe
        // Pour un usage production, il est recommandé d'utiliser une bibliothèque PHP locale
        return "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($qrCodeUrl);
    }

    /**
     * Vérifie un code OTP fourni par l'utilisateur
     *
     * @param string $secret Secret 2FA de l'utilisateur
     * @param string $code Code à vérifier (6 chiffres)
     * @param int $window Fenêtre de tolérance (défaut: 2 = 1 minute avant/après)
     * @return bool true si le code est valide, false sinon
     */
    public static function verifyCode(string $secret, string $code, int $window = 2): bool
    {
        // Supprime les espaces du code
        $code = str_replace(' ', '', $code);

        // Vérifie que le code est bien composé de 6 chiffres
        if (!preg_match('/^\d{6}$/', $code)) {
            return false;
        }

        try {
            return self::getGoogle2FA()->verifyKey($secret, $code, $window);
        } catch (\Exception $e) {
            // En cas d'erreur, retourne false
            return false;
        }
    }

    /**
     * Génère le code actuel (utile pour les tests)
     *
     * @param string $secret Secret 2FA
     * @return string Code OTP actuel
     */
    public static function getCurrentCode(string $secret): string
    {
        return self::getGoogle2FA()->getCurrentOtp($secret);
    }

    /**
     * Vérifie si un secret est valide
     *
     * @param string $secret Secret à vérifier
     * @return bool true si valide, false sinon
     */
    public static function isValidSecret(string $secret): bool
    {
        // Un secret valide fait généralement 16 ou 32 caractères (base32)
        $validLengths = [16, 32];
        return !empty($secret) && in_array(strlen($secret), $validLengths) && ctype_alnum($secret);
    }
}
