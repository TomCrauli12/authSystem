# Auth-system

Простая система регистрации и авторизации пользователей на PHP.

## Возможности

- регистрация пользователя
- вход и выход из аккаунта
- хеширование паролей
- проверка данных через валидатор
- csrf-защита форм
- работа с сессиями
- защищенная страница профиля
- подключение к MySQL через PDO
- автозагрузка классов через Composer

## Структура проекта

```text
authSystem/
├── app/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   └── RegisterController.php
│   │
│   ├── Core/
│   │   ├── Csrf.php
│   │   ├── Environment.php
│   │   ├── Session.php
│   │   └── Validator.php
│   │
│   ├── Database/
│   │   └── Database.php
│   │
│   ├── Middleware/
│   │   └── AuthMiddleware.php
│   │
│   ├── Repositories/
│   │   └── UserRepository.php
│   │
│   ├── Services/
│   │   ├── AuthService.php
│   │   └── RegisterService.php
│   │
│   └── Views/
│       ├── auth/
│       │   ├── login.php
│       │   └── register.php
│       └── profile/
│           └── user_profile.php
│
├── database/
│   └── schema.sql
│
├── include/
│   └── header.php
│
├── public/
│   └── index.php
│
├── style/
│   └── css/
│       └── header.css
│
├── .env.example
├── .gitignore
├── composer.json
└── README.md