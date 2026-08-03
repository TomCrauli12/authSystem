<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Session;
use App\Services\RememberMeService;

class AuthMiddleware{

    private static bool $rememberMeChecked = false;

    public static function restoreRememberedUser(): void{

        if (self::$rememberMeChecked) {
            return;
        }

        self::$rememberMeChecked = true;

        $rememberMeService = new RememberMeService();
        $rememberMeService->restoreSession();
    }

    public static function requireAuth(string $redirectTo = '../auth/login.php'): void{

        self::restoreRememberedUser();

        if (!Session::has('id')) {
            header("Location: {$redirectTo}");
            exit;
        }
    }

    public static function requireGuest(string $redirectTo = '../../../public/index.php'): void{

        self::restoreRememberedUser();

        if (Session::has('id')) {
            header("Location: {$redirectTo}");
            exit;
        }
    }
}
