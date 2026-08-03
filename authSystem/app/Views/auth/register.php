<?php

declare(strict_types=1);

use App\Core\Csrf;
use App\Core\Session;
use App\Middleware\AuthMiddleware;

require_once dirname(__DIR__, 3) . '/vendor/autoload.php';

AuthMiddleware::requireGuest();

$errors = Session::getFlash('errors', []);
$oldUserName = Session::getFlash('old_user_name', '');

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../style/css/header.css">
    <title>Регистрация</title>
</head>
<body>
    <?php require dirname(__DIR__, 3) . '/include/header.php'; ?>

    <main class="auth-page">
        <h2>Регистрация</h2>

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

        <form action="../../Controllers/RegisterController.php?action=register" method="post">
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
                minlength="2"
                maxlength="255"
                autocomplete="new-password"
                required
            >

            <label for="password_confirmation">Повторите пароль</label>
            <input
                type="password"
                name="password_confirmation"
                id="password_confirmation"
                minlength="2"
                maxlength="255"
                autocomplete="new-password"
                required
            >

            <button type="submit">Создать аккаунт</button>
        </form>

        <a href="login.php">Войти</a>
    </main>
</body>
</html>
