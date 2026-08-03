<?php

declare(strict_types=1);

use App\Core\Csrf;
use App\Core\Session;
use App\Middleware\AuthMiddleware;

require_once dirname(__DIR__, 3) . '/vendor/autoload.php';

AuthMiddleware::requireAuth();

$userNameErrors = Session::getFlash('user_name_errors', []);
$passwordErrors = Session::getFlash('password_errors', []);
$userNameSuccess = Session::getFlash('user_name_success');
$passwordSuccess = Session::getFlash('password_success');
$oldUserName = Session::getFlash('old_profile_user_name', Session::get('user_name', ''));

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../style/css/header.css">
    <title>Профиль</title>
</head>
<body>
    <?php require_once "../../../include/header.php"; ?>

    <main class="profile-page">
        <h2>Профиль</h2>

        <p>Имя: <?= htmlspecialchars((string) Session::get('user_name'), ENT_QUOTES, 'UTF-8') ?></p>
        <p>Роль: <?= htmlspecialchars((string) Session::get('role'), ENT_QUOTES, 'UTF-8') ?></p>

        <section class="profile-section">
            <h3>Изменить имя пользователя</h3>

            <?php if (!empty($userNameErrors)): ?>
                <div class="message error">
                    <ul>
                        <?php foreach ($userNameErrors as $fieldErrors): ?>
                            <?php foreach ($fieldErrors as $error): ?>
                                <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if ($userNameSuccess): ?>
                <div class="message success">
                    <?= htmlspecialchars($userNameSuccess, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <form action="../../Controllers/ProfileController.php?action=update_user_name" method="post">
                <input type="hidden" name="_token" value="<?= htmlspecialchars(Csrf::token(), ENT_QUOTES, 'UTF-8') ?>">

                <label for="user_name">Новое имя пользователя</label>
                <input
                    type="text"
                    name="user_name"
                    id="user_name"
                    value="<?= htmlspecialchars((string) $oldUserName, ENT_QUOTES, 'UTF-8') ?>"
                    minlength="2"
                    maxlength="100"
                    autocomplete="username"
                    required
                >

                <button type="submit">Обновить имя</button>
            </form>
        </section>

        <section class="profile-section">
            <h3>Изменить пароль</h3>

            <?php if (!empty($passwordErrors)): ?>
                <div class="message error">
                    <ul>
                        <?php foreach ($passwordErrors as $fieldErrors): ?>
                            <?php foreach ($fieldErrors as $error): ?>
                                <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if ($passwordSuccess): ?>
                <div class="message success">
                    <?= htmlspecialchars($passwordSuccess, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <form action="../../Controllers/ProfileController.php?action=update_password" method="post">
                <input type="hidden" name="_token" value="<?= htmlspecialchars(Csrf::token(), ENT_QUOTES, 'UTF-8') ?>">

                <label for="current_password">Текущий пароль</label>
                <input
                    type="password"
                    name="current_password"
                    id="current_password"
                    maxlength="255"
                    autocomplete="current-password"
                    required
                >

                <label for="new_password">Новый пароль</label>
                <input
                    type="password"
                    name="new_password"
                    id="new_password"
                    minlength="2"
                    maxlength="255"
                    autocomplete="new-password"
                    required
                >

                <label for="new_password_confirmation">Повторите новый пароль</label>
                <input
                    type="password"
                    name="new_password_confirmation"
                    id="new_password_confirmation"
                    minlength="2"
                    maxlength="255"
                    autocomplete="new-password"
                    required
                >

                <button type="submit">Изменить пароль</button>
            </form>
        </section>
    </main>
</body>
</html>
