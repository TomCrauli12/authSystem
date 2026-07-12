<?php

    require_once __DIR__ . '/../Repositories/UserRepository.php';

    class userRegister{

        static function register($user_name, $password, $role){

            $userRepository = new UserRepository();

            if ($userRepository->existsUserName($user_name)) {

                echo "<script>alert('Пользователь с таким именем уже существует'); window.location.href='../Views/auth/register.php';</script>";

                exit;
            }
            
            $userRepository->createUser($user_name, $password, $role);

        }

    }


?>