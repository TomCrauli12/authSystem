<?php

require_once __DIR__ . '/../Core/Session.php';

class AuthMiddleware{

    public static function requireAuth(string $redirectTo = '../auth/login.php'): void{

        Session::start();

        if (!Session::has('id')) {

            header("Location: {$redirectTo}");

            exit;
        }
    }

    public static function requireUser(string $redirectTo = '../../../public/index.php'): void{

        Session::start();

        if (Session::has('id')) {

            header("Location: {$redirectTo}");

            exit;
        }
    }
}