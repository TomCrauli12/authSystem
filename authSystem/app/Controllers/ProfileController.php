<?php

declare(strict_types=1);

use App\Core\Csrf;
use App\Core\Session;
use App\Core\Validator;
use App\Middleware\AuthMiddleware;
use App\Services\ProfileService;

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

AuthMiddleware::requireAuth('../Views/auth/login.php');

$action = $_GET['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../Views/profile/user_profile.php');
    exit;
}

$errorKey = $action === 'update_password' ? 'password_errors' : 'user_name_errors';

if (!Csrf::verify($_POST['_token'] ?? null)) {
    Session::setFlash($errorKey, [
        'csrf' => ['Сессия устарела. Обновите страницу и попробуйте еще раз'],
    ]);

    header('Location: ../Views/profile/user_profile.php');
    exit;
}

$userId = (int) Session::get('id');

if ($action === 'update_user_name') {
    $userName = is_string($_POST['user_name'] ?? null) ? trim($_POST['user_name']) : '';

    $validator = new Validator([
        'user_name' => $userName,
    ]);

    $validator
        ->required('user_name', 'Введите новое имя пользователя')
        ->min('user_name', 2, 'Имя пользователя должно быть минимум 2 символа')
        ->max('user_name', 100, 'Имя пользователя должно быть максимум 100 символов');

    if ($validator->fails()) {
        Session::setFlash('user_name_errors', $validator->errors());
        Session::setFlash('old_profile_user_name', $userName);

        header('Location: ../Views/profile/user_profile.php');
        exit;
    }

    $profileService = new ProfileService();

    if (!$profileService->updateUserName($userId, $userName)) {
        Session::setFlash('user_name_errors', [
            'user_name' => ['Пользователь с таким именем уже существует'],
        ]);
        Session::setFlash('old_profile_user_name', $userName);

        header('Location: ../Views/profile/user_profile.php');
        exit;
    }

    Session::setFlash('user_name_success', 'Имя пользователя обновлено');

    header('Location: ../Views/profile/user_profile.php');
    exit;
}

if ($action === 'update_password') {
    $currentPassword = is_string($_POST['current_password'] ?? null)
        ? $_POST['current_password']
        : '';
    $newPassword = is_string($_POST['new_password'] ?? null)
        ? $_POST['new_password']
        : '';
    $newPasswordConfirmation = is_string($_POST['new_password_confirmation'] ?? null)
        ? $_POST['new_password_confirmation']
        : '';

    $validator = new Validator([
        'current_password' => $currentPassword,
        'new_password' => $newPassword,
        'new_password_confirmation' => $newPasswordConfirmation,
    ]);

    $validator
        ->required('current_password', 'Введите текущий пароль')
        ->max('current_password', 255, 'Текущий пароль должен быть максимум 255 символов')
        ->required('new_password', 'Введите новый пароль')
        ->min('new_password', 2, 'Новый пароль должен быть минимум 2 символа')
        ->max('new_password', 255, 'Новый пароль должен быть максимум 255 символов')
        ->required('new_password_confirmation', 'Повторите новый пароль')
        ->same('new_password_confirmation', 'new_password', 'Новые пароли не совпадают');

    if ($validator->fails()) {
        Session::setFlash('password_errors', $validator->errors());

        header('Location: ../Views/profile/user_profile.php');
        exit;
    }

    $profileService = new ProfileService();

    if (!$profileService->changePassword($userId, $currentPassword, $newPassword)) {
        Session::setFlash('password_errors', [
            'current_password' => ['Текущий пароль введен неверно'],
        ]);

        header('Location: ../Views/profile/user_profile.php');
        exit;
    }

    Session::setFlash('password_success', 'Пароль успешно изменен');

    header('Location: ../Views/profile/user_profile.php');
    exit;
}

header('Location: ../Views/profile/user_profile.php');
exit;
