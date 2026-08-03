<?php

declare(strict_types=1);

namespace App\Core;

final readonly class RememberToken{

    private const SELECTOR_LENGTH = 32;
    private const VALIDATOR_LENGTH = 64;

    public function __construct(
        public string $selector,
        public string $validator
    ){
    }

    public static function create(): self{

        return new self(
            bin2hex(random_bytes(16)),
            bin2hex(random_bytes(32))
        );
    }

    public static function fromCookie(string $cookie): ?self{

        $parts = explode(':', $cookie, 2);

        if (count($parts) !== 2) {
            return null;
        }

        [$selector, $validator] = $parts;

        if (
            strlen($selector) !== self::SELECTOR_LENGTH
            || strlen($validator) !== self::VALIDATOR_LENGTH
            || !ctype_xdigit($selector)
            || !ctype_xdigit($validator)
        ) {
            return null;
        }

        return new self(strtolower($selector), strtolower($validator));
    }

    public function cookieValue(): string{

        return "{$this->selector}:{$this->validator}";
    }

    public function hash(): string{

        return hash('sha256', $this->validator);
    }
}
