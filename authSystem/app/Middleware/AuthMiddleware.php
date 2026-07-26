<?php

namespace App\Middleware;

use App\Core\Session;

class AuthMiddleware{

    public static function requireAuth(string $redirectTo = '../auth/login.php'): void{

        if (!Session::has('id')) {
            header("Location: {$redirectTo}");
            exit;
        }
    }

    public static function requireGuest(string $redirectTo = '../../../public/index.php'): void{

        if (Session::has('id')) {
            header("Location: {$redirectTo}");
            exit;
        }
    }
}



?>