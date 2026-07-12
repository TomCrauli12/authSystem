<?php

    class DB{

        public static function getConnection(){

            $host = 'localhost';
            $dbname = 'authsystem';
            $user = 'root';
            $password = 'root';

            $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $password);

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

            return $conn;
        }
    }