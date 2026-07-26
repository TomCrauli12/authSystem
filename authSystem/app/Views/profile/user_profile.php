<?php

use App\Core\Session;
use App\Middleware\AuthMiddleware;

require_once dirname(__DIR__, 3) . '/vendor/autoload.php';

AuthMiddleware::requireAuth();

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
        <h1>Профиль</h1>
        <p>Имя: <?=htmlspecialchars(Session::get('user_name'), ENT_QUOTES, 'UTF-8')?></p>
        <p>Роль: <?=htmlspecialchars(Session::get('role'), ENT_QUOTES, 'UTF-8')?></p>
    </main>
</body>
</html>
