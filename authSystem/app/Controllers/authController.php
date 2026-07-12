<?php

    require_once "../Services/authServise.php";
    require_once "../Core/Validator.php";
    require_once "../Core/Session.php";

    if (!isset($_GET['action'])) {

        header("Location: ../Views/auth/login.php");

        exit;
    }

    if ($_GET['action'] === "identification") {
        $user_name = $_POST['user_name'] ?? '';
        $password = $_POST['password'] ?? '';

        $validator = new Validator($_POST);

        $validator
            ->required('user_name', 'Введите имя пользователя')
            ->required('password', 'Введите пароль');

        if ($validator->fails()) {

            $message = $validator->firstError();

            echo "<script>alert('{$message}'); window.location.href='../Views/auth/login.php';</script>";

            exit;
        }

        userIdentification::identification($user_name, $password);
        
        exit;
    }

    elseif ($_GET['action'] === "logout") {

        Session::destroy();

        header("Location: ../Views/auth/login.php");

        exit;
    }

    header("Location: ../Views/auth/login.php");
    exit;

?>