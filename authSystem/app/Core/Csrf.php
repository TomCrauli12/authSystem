<?php

namespace App\Core;

class Csrf{

    private const TOKEN_KEY = '_csrf_token';

    public static function token(): string{

        $token = Session::get(self::TOKEN_KEY);

        if (!is_string($token) || $token === '') {
            $token = bin2hex(random_bytes(32));
            Session::set(self::TOKEN_KEY, $token);
        }

        return $token;
    }

    public static function verify(mixed $token): bool{

        $storedToken = Session::get(self::TOKEN_KEY);

        return is_string($token)
            && is_string($storedToken)
            && $storedToken !== ''
            && hash_equals($storedToken, $token);
    }

    public static function regenerate(): void{

        Session::set(self::TOKEN_KEY, bin2hex(random_bytes(32)));
    }
}
