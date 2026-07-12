<?php

    require_once __DIR__ . '/../../Middleware/AuthMiddleware.php';

    AuthMiddleware::requireUser();

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="../../Controllers/authController.php?action=identification" method="post">

        <label for="user_name">Name</label>
        <input type="text" name="user_name" id="">

        <label for="password">password</label>
        <input type="password" name="password" id="">

        <button>Войти</button>

    </form>
    <a href="../auth/register.php">Создать аккаунт</a>
</body>
</html>