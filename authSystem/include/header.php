<?php
require_once __DIR__ . '/../app/Core/Session.php';

?>

<header>
    <div class="logo">
        <a href="/pure-php-auth-system/public/index.php">
            <h1>Auth-system</h1>
        </a>
    </div>

    <div class="info">
        <ul>
            <li><a href="">О нас</a></li>
            <li><a href="">Отзывы</a></li>
            <li><a href="">Связь с нами</a></li>
        </ul>
    </div>

    <div class="login">
        <?php if (isset($_SESSION['id'])): ?>
            <div class="prof">
                <div class="user">
                    <div class="ava">
                        <img src="" alt="фото пользователя">
                    </div>

                    <div class="user_name">
                        <a href="/pure-php-auth-system/app/Views/profile/user_profile.php"><?=htmlspecialchars(Session::get('user_name'), ENT_QUOTES, 'UTF-8') ?></a>

                        <a href="/pure-php-auth-system/app/Controllers/authController.php?action=logout">Выйти</a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="aund">
                <a href="/pure-php-auth-system/app/Views/auth/login.php">Войти / Зарегистрироваться</a>
            </div>
        <?php endif; ?>
    </div>
</header>