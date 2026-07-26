<?php

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
    Session::setFlash('error', 'Сессия устарела. Обновите страницу и попробуйте еще раз');
    header('Location: ../Views/auth/register.php');
    exit;
}

$userName = is_string($_POST['user_name'] ?? null) ? trim($_POST['user_name']) : '';
$password = is_string($_POST['password'] ?? null) ? $_POST['password'] : '';

$validator = new Validator([
    'user_name' => $userName,
    'password' => $password,
]);

$validator
    ->required('user_name', 'Введите имя пользователя')
    ->min('user_name', 2, 'Имя пользователя должно быть минимум 2 символа')
    ->max('user_name', 100, 'Имя пользователя должно быть максимум 100 символов')
    ->required('password', 'Введите пароль')
    ->min('password', 2, 'Пароль должен быть минимум 2 символа');

if ($validator->fails()) {

    Session::setFlash('error', implode('<br>', $validator->allErrors()));
    Session::setFlash('old_user_name', $userName);

    header('Location: ../Views/auth/register.php');
    exit;
}

$registerService = new RegisterService();

if (!$registerService->register($userName, $password)) {

    Session::setFlash('error', 'Пользователь с таким именем уже существует');
    Session::setFlash('old_user_name', $userName);

    header('Location: ../Views/auth/register.php');
    exit;
}

Csrf::regenerate();
Session::setFlash('success', 'Аккаунт создан. Теперь можно войти');

header('Location: ../Views/auth/login.php');
exit;




?>