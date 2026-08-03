<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Csrf;
use App\Core\Session;
use App\Repositories\UserRepository;
use PDOException;

final class ProfileService{

    public function __construct(
        private readonly UserRepository $userRepository = new UserRepository(),
        private readonly RememberMeService $rememberMeService = new RememberMeService()
    ){
    }

    public function updateUserName(int $userId, string $userName): bool{

        if ($this->userRepository->userNameExistsExceptId($userName, $userId)) {
            return false;
        }

        try {
            $this->userRepository->updateUserName($userId, $userName);
        } catch (PDOException $exception) {
            if ($exception->getCode() === '23000') {
                return false;
            }

            throw $exception;
        }

        Session::set('user_name', $userName);

        return true;
    }

    public function changePassword(int $userId, string $currentPassword, string $newPassword): bool{

        $user = $this->userRepository->findByIdWithPassword($userId);

        if (!$user || !password_verify($currentPassword, $user['password'])) {
            return false;
        }

        $passwordHash = password_hash($newPassword, PASSWORD_ARGON2ID);

        $this->userRepository->updatePassword($userId, $passwordHash);
        $this->rememberMeService->forgetAllForUser($userId);

        Session::regenerate();
        Csrf::regenerate();

        return true;
    }
}
