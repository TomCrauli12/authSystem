<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Environment;
use App\Core\RememberToken;
use App\Core\Session;
use App\Repositories\RememberTokenRepository;
use App\Repositories\UserRepository;
use DateTimeImmutable;
use DateTimeZone;

final class RememberMeService{

    private const COOKIE_NAME = 'remember_token';
    private const LIFETIME_DAYS = 30;

    public function __construct(
        private ?RememberTokenRepository $rememberTokenRepository = null,
        private ?UserRepository $userRepository = null
    ){

        Environment::load(dirname(__DIR__, 2) . '/.env');
    }

    public function remember(int $userId): void{

        $this->forget();

        $now = $this->now();
        $expiresAt = $now->modify('+' . self::LIFETIME_DAYS . ' days');
        $rememberToken = RememberToken::create();

        $this->rememberTokenRepository()->deleteExpired($now);
        $this->rememberTokenRepository()->create(
            $userId,
            $rememberToken->selector,
            $rememberToken->hash(),
            $expiresAt
        );

        $this->setCookie($rememberToken, $expiresAt);
    }

    public function restoreSession(): void{

        $cookie = $_COOKIE[self::COOKIE_NAME] ?? null;

        if (!is_string($cookie) || $cookie === '') {
            return;
        }

        $rememberToken = RememberToken::fromCookie($cookie);

        if (!$rememberToken) {
            $this->clearCookie();
            return;
        }

        $storedToken = $this->rememberTokenRepository()->findBySelector($rememberToken->selector);

        if (!$storedToken) {
            $this->clearCookie();
            return;
        }

        $storedHash = $storedToken['token_hash'] ?? null;
        $storedExpiresAt = $storedToken['expires_at'] ?? null;

        if (!is_string($storedHash) || !is_string($storedExpiresAt)) {
            $this->forget();
            return;
        }

        $expiresAt = DateTimeImmutable::createFromFormat(
            'Y-m-d H:i:s',
            $storedExpiresAt,
            new DateTimeZone('UTC')
        );

        if (!$expiresAt || $expiresAt <= $this->now()) {
            $this->forget();
            return;
        }

        if (!hash_equals($storedHash, $rememberToken->hash())) {
            $this->forget();
            return;
        }

        $userId = (int) ($storedToken['user_id'] ?? 0);

        if ($userId <= 0) {
            $this->forget();
            return;
        }

        if (Session::has('id') && (int) Session::get('id') !== $userId) {
            $this->forget();
            return;
        }

        $user = $this->userRepository()->findById($userId);

        if (!$user) {
            $this->forget();
            return;
        }

        if (!Session::has('id')) {
            Session::regenerate();
        }

        Session::set('id', (int) $user['id']);
        Session::set('user_name', $user['user_name']);
        Session::set('role', $user['role']);

        $this->rotate($rememberToken);
    }

    public function forget(): void{

        $cookie = $_COOKIE[self::COOKIE_NAME] ?? null;

        if (is_string($cookie)) {
            $rememberToken = RememberToken::fromCookie($cookie);

            if ($rememberToken) {
                $this->rememberTokenRepository()->deleteBySelector($rememberToken->selector);
            }
        }

        $this->clearCookie();
    }

    public function forgetAllForUser(int $userId): void{

        $this->rememberTokenRepository()->deleteByUserId($userId);

        $this->clearCookie();
    }

    private function rotate(RememberToken $oldToken): void{

        $newToken = RememberToken::create();
        $expiresAt = $this->now()->modify('+' . self::LIFETIME_DAYS . ' days');

        $rotated = $this->rememberTokenRepository()->rotate(
            $oldToken->selector,
            $newToken->selector,
            $newToken->hash(),
            $expiresAt
        );

        if (!$rotated) {
            $this->clearCookie();
            return;
        }

        $this->setCookie($newToken, $expiresAt);
    }

    private function setCookie(RememberToken $rememberToken, DateTimeImmutable $expiresAt): void{

        setcookie(self::COOKIE_NAME, $rememberToken->cookieValue(), [
            'expires' => $expiresAt->getTimestamp(),
            'path' => $this->cookiePath(),
            'secure' => $this->isHttps(),
            'httponly' => true,
            'samesite' => 'Lax',
        ]);

        $_COOKIE[self::COOKIE_NAME] = $rememberToken->cookieValue();
    }

    private function clearCookie(): void{

        setcookie(self::COOKIE_NAME, '', [
            'expires' => time() - 3600,
            'path' => $this->cookiePath(),
            'secure' => $this->isHttps(),
            'httponly' => true,
            'samesite' => 'Lax',
        ]);

        unset($_COOKIE[self::COOKIE_NAME]);
    }

    private function rememberTokenRepository(): RememberTokenRepository{

        $this->rememberTokenRepository ??= new RememberTokenRepository();

        return $this->rememberTokenRepository;
    }

    private function userRepository(): UserRepository{

        $this->userRepository ??= new UserRepository();

        return $this->userRepository;
    }

    private function now(): DateTimeImmutable{

        return new DateTimeImmutable('now', new DateTimeZone('UTC'));
    }

    private function cookiePath(): string{

        $path = getenv('APP_BASE_PATH');

        if (!is_string($path) || $path === '') {
            return '/authSystem';
        }

        return '/' . trim($path, '/');
    }

    private function isHttps(): bool{

        return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (int) ($_SERVER['SERVER_PORT'] ?? 0) === 443;
    }
}
