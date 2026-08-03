<?php

declare(strict_types=1);

use App\Core\Csrf;
use App\Core\Session;
use App\Middleware\AuthMiddleware;

require_once dirname(__DIR__, 3) . '/vendor/autoload.php';

AuthMiddleware::requireGuest();

$errors = Session::getFlash('errors', []);
$success = Session::getFlash('success');
$oldUserName = Session::getFlash('old_user_name', '');
$oldRememberMe = Session::getFlash('old_remember_me', false);

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../style/css/header.css">
    <title>Вход</title>
</head>
<body>
    <?php require dirname(__DIR__, 3) . '/include/header.php'; ?>

    <main class="auth-page">
        <h2>Вход</h2>

        <?php if (!empty($errors)): ?>
            <div class="message error">
                <ul>
                    <?php foreach ($errors as $fieldErrors): ?>
                        <?php foreach ($fieldErrors as $error): ?>
                            <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="message success"><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>

        <form action="../../Controllers/AuthController.php?action=login" method="post">
            <input type="hidden" name="_token" value="<?= htmlspecialchars(Csrf::token(), ENT_QUOTES, 'UTF-8') ?>">

            <label for="user_name">Имя пользователя</label>
            <input
                type="text"
                name="user_name"
                id="user_name"
                value="<?= htmlspecialchars($oldUserName, ENT_QUOTES, 'UTF-8') ?>"
                maxlength="100"
                autocomplete="username"
                required
            >

            <label for="password">Пароль</label>
            <input
                type="password"
                name="password"
                id="password"
                maxlength="255"
                autocomplete="current-password"
                required
            >

            <label class="remember-me" for="remember_me">
                <input
                    type="checkbox"
                    name="remember_me"
                    id="remember_me"
                    value="1"
                    <?= $oldRememberMe ? 'checked' : '' ?>
                >
                <span>Запомнить меня</span>
            </label>

            <button type="submit">Войти</button>
        </form>

        <a href="register.php">Создать аккаунт</a>
    </main>
</body>
</html>
