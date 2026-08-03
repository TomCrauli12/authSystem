<?php

declare(strict_types=1);

use App\Core\RememberToken;
use App\Core\Validator;
use App\Services\ProfileService;

require_once dirname(__DIR__) . '/vendor/autoload.php';

function check(bool $condition, string $message): void{

    if (!$condition) {
        throw new RuntimeException($message);
    }
}

$rememberToken = RememberToken::create();
$restoredToken = RememberToken::fromCookie($rememberToken->cookieValue());

check($restoredToken !== null, 'Токен не восстановился из cookie');
check($restoredToken->selector === $rememberToken->selector, 'Selector не совпадает');
check($restoredToken->validator === $rememberToken->validator, 'Validator токена не совпадает');
check(strlen($rememberToken->hash()) === 64, 'Хеш токена должен содержать 64 символа');
check(RememberToken::fromCookie('incorrect-token') === null, 'Некорректный токен должен отклоняться');

$validator = new Validator([
    'password' => '12',
    'password_confirmation' => '21',
]);

$validator->same('password_confirmation', 'password', 'Пароли не совпадают');

check($validator->fails(), 'Валидатор должен найти несовпадение паролей');

$passwordHash = password_hash('12', PASSWORD_ARGON2ID);

check(password_get_info($passwordHash)['algoName'] === 'argon2id', 'Пароль должен использовать Argon2id');
check(password_verify('12', $passwordHash), 'Правильный пароль должен проходить проверку');
check(class_exists(ProfileService::class), 'Сервис профиля должен загружаться через Composer');

echo "Все тесты пройдены", PHP_EOL;
