<?php
require_once __DIR__ . '/../../Core/Session.php';
require_once __DIR__ . '/../../Middleware/AuthMiddleware.php';

AuthMiddleware::requireAuth();

?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../style/css/header.css">   
    <title>Профиль</title>
</head>
<body>
    <?php require_once "../../../include/header.php"; ?>
    <p><?= htmlspecialchars($_SESSION['user_name'], ENT_QUOTES, 'UTF-8') ?></p>
</body>
</html>