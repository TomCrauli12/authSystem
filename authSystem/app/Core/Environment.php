<?php

namespace App\Core;

class Environment{

    public static function load(string $file): void{

        if (!is_file($file) || !is_readable($file)) {
            return;
        }

        $variables = parse_ini_file($file, false, INI_SCANNER_RAW);

        if (!is_array($variables)) {
            return;
        }

        foreach ($variables as $key => $value) {
            if (!is_string($key) || !is_string($value) || getenv($key) !== false) {
                continue;
            }

            putenv("{$key}={$value}");
            $_ENV[$key] = $value;
        }
    }
}
