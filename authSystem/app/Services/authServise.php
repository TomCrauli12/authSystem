<?php

    require_once __DIR__ . '/../Repositories/UserRepository.php';
    require_once __DIR__ . '/../Core/Session.php';

    class userIdentification{

        public static function identification($user_name, $password){

            Session::start();

            $userRepository = new UserRepository();

            $userinfo = $userRepository->findUserName($user_name);

            if (!$userinfo || !password_verify($password, $userinfo['password'])) {

                echo "<script type='text/javascript'>alert('Ошибка авторизации'); window.location.href='../Views/auth/login.php';</script>";

                exit;
            }

            Session::regenerate();

            Session::set('id', $userinfo['id']);

            Session::set('user_name', $userinfo['user_name']);
            
            Session::set('role', $userinfo['role']);

            header('Location: ../../public/index.php');

            exit;
        }
    }




?>