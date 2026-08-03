<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\Database;
use DateTimeImmutable;
use PDO;

final class RememberTokenRepository{

    private PDO $conn;

    public function __construct(?PDO $conn = null){

        $this->conn = $conn ?? Database::getConnection();
    }

    public function create(
        int $userId,
        string $selector,
        string $tokenHash,
        DateTimeImmutable $expiresAt
    ): void{

        $query = $this->conn->prepare(
            "INSERT INTO `remember_tokens` (`user_id`, `selector`, `token_hash`, `expires_at`)
             VALUES (?, ?, ?, ?)"
        );

        $query->execute([
            $userId,
            $selector,
            $tokenHash,
            $expiresAt->format('Y-m-d H:i:s'),
        ]);
    }

    public function findBySelector(string $selector): ?array{

        $query = $this->conn->prepare(
            "SELECT `id`, `user_id`, `selector`, `token_hash`, `expires_at`
             FROM `remember_tokens`
             WHERE `selector` = ?
             LIMIT 1"
        );

        $query->execute([$selector]);

        $rememberToken = $query->fetch();

        return $rememberToken ?: null;
    }

    public function rotate(
        string $oldSelector,
        string $newSelector,
        string $tokenHash,
        DateTimeImmutable $expiresAt
    ): bool{

        $query = $this->conn->prepare(
            "UPDATE `remember_tokens`
             SET `selector` = ?, `token_hash` = ?, `expires_at` = ?
             WHERE `selector` = ?"
        );

        $query->execute([
            $newSelector,
            $tokenHash,
            $expiresAt->format('Y-m-d H:i:s'),
            $oldSelector,
        ]);

        return $query->rowCount() === 1;
    }

    public function deleteBySelector(string $selector): void{

        $query = $this->conn->prepare("DELETE FROM `remember_tokens` WHERE `selector` = ?");

        $query->execute([$selector]);
    }

    public function deleteByUserId(int $userId): void{

        $query = $this->conn->prepare("DELETE FROM `remember_tokens` WHERE `user_id` = ?");

        $query->execute([$userId]);
    }

    public function deleteExpired(DateTimeImmutable $now): void{

        $query = $this->conn->prepare("DELETE FROM `remember_tokens` WHERE `expires_at` <= ?");

        $query->execute([$now->format('Y-m-d H:i:s')]);
    }
}
