<?php


namespace utils;


class SessionHelpers
{
    public function __construct()
    {
        SessionHelpers::init();
    }

    static function init(): void
    {
        session_start();
    }

    static function login(mixed $equipe): void
    {
        $_SESSION['LOGIN'] = $equipe;
    }

    static function logout(): void
    {
        unset($_SESSION['LOGIN']);
    }

    static function getConnected(): mixed
    {
        if (SessionHelpers::isLogin()) {
            return $_SESSION['LOGIN'];
        } else {
            return array();
        }
    }

    static function isLogin(): bool
    {
        return isset($_SESSION['LOGIN']);
    }
}