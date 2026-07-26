<?php

namespace App\Services;

use App\Repositories\UserRepository;

class RegisterService{

    public function __construct(
        private readonly UserRepository $userRepository = new UserRepository()
    ){
    }

    public function register(string $userName, string $password): bool{

        if ($this->userRepository->userNameExists($userName)) {
            return false;
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $this->userRepository->create($userName, $passwordHash);

        return true;
    }
}



?>