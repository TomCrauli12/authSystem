<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\Database;
use PDO;

class UserRepository{

    private PDO $conn;

    public function __construct(){

        $this->conn = Database::getConnection();
    }

    public function findByUserName(string $userName): ?array{

        $query = $this->conn->prepare(
            "SELECT `id`, `user_name`, `password`, `role` FROM `users` WHERE `user_name` = ? LIMIT 1"
        );

        $query->execute([$userName]);

        $user = $query->fetch();

        return $user ?: null;
    }

    public function findById(int $id): ?array{

        $query = $this->conn->prepare(
            "SELECT `id`, `user_name`, `role` FROM `users` WHERE `id` = ? LIMIT 1"
        );

        $query->execute([$id]);

        $user = $query->fetch();

        return $user ?: null;
    }

    public function findByIdWithPassword(int $id): ?array{

        $query = $this->conn->prepare(
            "SELECT `id`, `user_name`, `password`, `role` FROM `users` WHERE `id` = ? LIMIT 1"
        );

        $query->execute([$id]);

        $user = $query->fetch();

        return $user ?: null;
    }

    public function userNameExists(string $userName): bool{

        $query = $this->conn->prepare("SELECT COUNT(*) FROM `users` WHERE `user_name` = ?");

        $query->execute([$userName]);

        return (int) $query->fetchColumn() > 0;
    }

    public function userNameExistsExceptId(string $userName, int $userId): bool{

        $query = $this->conn->prepare(
            "SELECT COUNT(*) FROM `users` WHERE `user_name` = ? AND `id` != ?"
        );

        $query->execute([$userName, $userId]);

        return (int) $query->fetchColumn() > 0;
    }

    public function create(string $userName, string $password, string $role = 'user'): int{

        $query = $this->conn->prepare(
            "INSERT INTO `users` (`user_name`, `password`, `role`) VALUES (?, ?, ?)"
        );

        $query->execute([$userName, $password, $role]);

        return (int) $this->conn->lastInsertId();
    }

    public function updateUserName(int $userId, string $userName): void{

        $query = $this->conn->prepare("UPDATE `users` SET `user_name` = ? WHERE `id` = ?");

        $query->execute([$userName, $userId]);
    }

    public function updatePassword(int $userId, string $password): void{

        $query = $this->conn->prepare("UPDATE `users` SET `password` = ? WHERE `id` = ?");

        $query->execute([$password, $userId]);
    }
}
