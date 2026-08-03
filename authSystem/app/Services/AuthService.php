<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Csrf;
use App\Core\Session;
use App\Repositories\UserRepository;

class AuthService{

    public function __construct(
        private ?UserRepository $userRepository = null,
        private ?RememberMeService $rememberMeService = null
    ){
    }

    public function login(string $userName, string $password, bool $rememberMe = false): bool{

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

        $this->rememberMeService ??= new RememberMeService();

        if ($rememberMe) {
            $this->rememberMeService->remember((int) $user['id']);
        } else {
            $this->rememberMeService->forget();
        }

        return true;
    }

    public function logout(): void{

        $this->rememberMeService ??= new RememberMeService();
        $this->rememberMeService->forget();

        Session::destroy();
    }
}
