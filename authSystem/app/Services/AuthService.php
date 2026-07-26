<?php

namespace App\Services;

use App\Core\Csrf;
use App\Core\Session;
use App\Repositories\UserRepository;

class AuthService{

    public function __construct(
        private ?UserRepository $userRepository = null
    ){
    }

    public function login(string $userName, string $password): bool{

        $this->userRepository ??= new UserRepository();

        $user = $this->userRepository->findByUserName($userName);

        if (!$user || !password_verify($password, $user['password'])) {
            
            return false;
        }

        Session::regenerate();
        Session::set('id', (int) $user['id']);
        Session::set('user_name', $user['user_name']);
        Session::set('role', $user['role']);

        Csrf::regenerate();

        return true;
    }

    public function logout(): void{

        Session::destroy();
    }
}

?>