<?php

    require_once __DIR__ . '/../../database/BD.php';

    class UserRepository{

        private PDO $conn;

        public function __construct(){

            $this->conn = DB::getConnection();

        }

        public function findUserName(string $user_name): ?array{

            $query = $this->conn->prepare("SELECT * FROM `users` WHERE `user_name` = ? LIMIT 1");

            $query->execute([$user_name]);

            $user = $query->fetch(PDO::FETCH_ASSOC);

            return $user ?: null;
        }

        public function existsUserName(string $user_name): bool{

            $query = $this->conn->prepare("SELECT COUNT(*) FROM `users` WHERE `user_name` = ?");

            $query->execute([$user_name]);

            return (int) $query->fetchColumn() > 0;

        }

        public function createUser(string $user_name, string $password, string $role): void{

            $query = $this->conn->prepare("INSERT INTO `users` (`user_name`, `password`, `role`) VALUES (?, ?, ?)");

            $query->execute([$user_name, $password, $role]);

        }





    }

?>