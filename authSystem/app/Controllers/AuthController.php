<?php

declare(strict_types=1);

use App\Core\Csrf;
use App\Core\Session;
use App\Core\Validator;
use App\Services\AuthService;

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

$action = $_GET['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../Views/auth/login.php');
    exit;
}

if (!Csrf::verify($_POST['_token'] ?? null)) {
    Session::setFlash('errors', [
        'csrf' => ['Сессия устарела. Обновите страницу и попробуйте еще раз'],
    ]);
    header('Location: ../Views/auth/login.php');
    exit;
}

if ($action === 'login') {
    $userName = is_string($_POST['user_name'] ?? null) ? trim($_POST['user_name']) : '';
    $password = is_string($_POST['password'] ?? null) ? $_POST['password'] : '';
    $rememberMe = ($_POST['remember_me'] ?? '') === '1';

    $validator = new Validator([
        'user_name' => $userName,
        'password' => $password,
    ]);

    $validator
        ->required('user_name', 'Введите имя пользователя')
        ->max('user_name', 100, 'Имя пользователя должно быть максимум 100 символов')
        ->required('password', 'Введите пароль')
        ->max('password', 255, 'Пароль должен быть максимум 255 символов');

    if ($validator->fails()) {
        Session::setFlash('errors', $validator->errors());
        Session::setFlash('old_user_name', $userName);
        Session::setFlash('old_remember_me', $rememberMe);

        header('Location: ../Views/auth/login.php');
        exit;
    }

    $authService = new AuthService();

    if (!$authService->login($userName, $password, $rememberMe)) {
        Session::setFlash('errors', [
            'auth' => ['Неверное имя пользователя или пароль'],
        ]);
        Session::setFlash('old_user_name', $userName);
        Session::setFlash('old_remember_me', $rememberMe);

        header('Location: ../Views/auth/login.php');
        exit;
    }

    header('Location: ../../public/index.php');
    exit;
}

if ($action === 'logout') {
    $authService = new AuthService();
    $authService->logout();

    header('Location: ../Views/auth/login.php');
    exit;
}

header('Location: ../Views/auth/login.php');
exit;
