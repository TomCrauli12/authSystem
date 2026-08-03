<?php

declare(strict_types=1);

use App\Middleware\AuthMiddleware;

require_once "../vendor/autoload.php";

AuthMiddleware::restoreRememberedUser();

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/css/header.css">
    <title>Auth-system</title>
</head>
<body>
    <?php require_once "../include/header.php"; ?>

    <main class="home-page">
        <h2>Главная страница</h2>
    </main>
</body>
</html>
