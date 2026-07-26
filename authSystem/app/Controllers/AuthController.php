<?php

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
    Session::setFlash('error', 'Сессия устарела. Обновите страницу и попробуйте еще раз');
    header('Location: ../Views/auth/login.php');
    exit;
}

if ($action === 'login') {
    $userName = is_string($_POST['user_name'] ?? null) ? trim($_POST['user_name']) : '';
    $password = is_string($_POST['password'] ?? null) ? $_POST['password'] : '';

    $validator = new Validator([
        'user_name' => $userName,
        'password' => $password,
    ]);

    $validator
        ->required('user_name', 'Введите имя пользователя')
        ->required('password', 'Введите пароль');

    if ($validator->fails()) {
        Session::setFlash('error', $validator->firstError());
        Session::setFlash('old_user_name', $userName);

        header('Location: ../Views/auth/login.php');
        exit;
    }

    $authService = new AuthService();

    if (!$authService->login($userName, $password)) {

        Session::setFlash('error', 'Неверное имя пользователя или пароль');

        Session::setFlash('old_user_name', $userName);

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



?>