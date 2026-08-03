<?php

declare(strict_types=1);

use App\Core\Csrf;
use App\Core\Session;
use App\Core\Validator;
use App\Services\RegisterService;

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || ($_GET['action'] ?? '') !== 'register') {
    header('Location: ../Views/auth/register.php');
    exit;
}

if (!Csrf::verify($_POST['_token'] ?? null)) {
    Session::setFlash('errors', [
        'csrf' => ['Сессия устарела. Обновите страницу и попробуйте еще раз'],
    ]);
    header('Location: ../Views/auth/register.php');
    exit;
}

$userName = is_string($_POST['user_name'] ?? null) ? trim($_POST['user_name']) : '';
$password = is_string($_POST['password'] ?? null) ? $_POST['password'] : '';
$passwordConfirmation = is_string($_POST['password_confirmation'] ?? null)
    ? $_POST['password_confirmation']
    : '';

$validator = new Validator([
    'user_name' => $userName,
    'password' => $password,
    'password_confirmation' => $passwordConfirmation,
]);

$validator
    ->required('user_name', 'Введите имя пользователя')
    ->min('user_name', 2, 'Имя пользователя должно быть минимум 2 символа')
    ->max('user_name', 100, 'Имя пользователя должно быть максимум 100 символов')
    ->required('password', 'Введите пароль')
    ->min('password', 2, 'Пароль должен быть минимум 2 символа')
    ->max('password', 255, 'Пароль должен быть максимум 255 символов')
    ->required('password_confirmation', 'Повторите пароль')
    ->same('password_confirmation', 'password', 'Пароли не совпадают');

if ($validator->fails()) {
    Session::setFlash('errors', $validator->errors());
    Session::setFlash('old_user_name', $userName);

    header('Location: ../Views/auth/register.php');
    exit;
}

$registerService = new RegisterService();

if (!$registerService->register($userName, $password)) {
    Session::setFlash('errors', [
        'user_name' => ['Пользователь с таким именем уже существует'],
    ]);
    Session::setFlash('old_user_name', $userName);

    header('Location: ../Views/auth/register.php');
    exit;
}

Csrf::regenerate();
Session::setFlash('success', 'Аккаунт создан. Теперь можно войти');

header('Location: ../Views/auth/login.php');
exit;
