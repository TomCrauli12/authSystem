<?php

use App\Core\Csrf;
use App\Core\Session;

require_once dirname(__DIR__) . '/vendor/autoload.php';

?>

<header>
    <div class="logo">
        <a href="/authSystem/public/index.php">
            <h1>Auth-system</h1>
        </a>
    </div>

    <nav class="info">
        <ul>
            <li><a href="#">О нас</a></li>
            <li><a href="#">Отзывы</a></li>
            <li><a href="#">Связь с нами</a></li>
        </ul>
    </nav>

    <div class="login">
        <?php if (Session::has('id')): ?>
            <div class="user">
                <a href="/authSystem/app/Views/profile/user_profile.php">
                    <?=htmlspecialchars(Session::get('user_name'), ENT_QUOTES, 'UTF-8')?>
                </a>

                <form action="/authSystem/app/Controllers/AuthController.php?action=logout" method="post">
                    <input type="hidden" name="_token" value="<?=htmlspecialchars(Csrf::token(), ENT_QUOTES, 'UTF-8')?>">
                    <button class="link-button" type="submit">Выйти</button>
                </form>
            </div>
        <?php else: ?>
            <a href="/authSystem/app/Views/auth/login.php">Войти / Зарегистрироваться</a>
        <?php endif; ?>
    </div>
</header>
