<?php

    require_once "../Services/registerServise.php";
    require_once "../Core/Validator.php";

    if (!isset($_GET['action'])) {

        header("Location: ../Views/auth/register.php");

        exit;
    }

    if ($_GET['action'] === "register") {

        $user_name = $_POST['user_name'] ?? '';
        $rawPassword = $_POST['password'] ?? '';
        $role = "user";

        $validator = new Validator($_POST);

        $validator
            ->required('user_name', 'Введите имя пользователя')
            ->min('user_name', 2, 'Имя пользователя должно быть минимум 2 символа')
            ->required('password', 'Введите пароль')
            ->min('password', 2, 'Пароль должен быть минимум 2 символов');

        if ($validator->fails()) {

            $errors = $validator->allErrors();

            $message = implode("\n", $errors);

            echo "<script> alert(" . json_encode($message, JSON_UNESCAPED_UNICODE) . "); window.location.href='../Views/auth/register.php'; </script>";
            
            exit;
        }

        $password = password_hash($rawPassword, PASSWORD_DEFAULT);

        userRegister::register($user_name, $password, $role);

        header("Location: ../Views/auth/login.php");

        exit;
    }

    header("Location: ../Views/auth/register.php");
    exit;
    
?>