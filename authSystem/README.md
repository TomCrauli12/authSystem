# Auth-system

Простая система регистрации и авторизации пользователей на PHP и MySQL.

## Возможности

- регистрация пользователя
- проверка имени и пароля
- повторный ввод пароля при регистрации
- хеширование паролей через Argon2id
- вход и выход из аккаунта
- функция «Запомнить меня» на 30 дней
- автоматическое обновление постоянного токена при посещении сайта
- хранение в базе только безопасного хеша токена
- защищенная страница профиля
- изменение имени с проверкой на занятость
- смена пароля с проверкой текущего пароля
- удаление постоянных токенов со всех устройств после смены пароля
- CSRF-защита всех форм
- безопасный вывод ошибок
- работа с базой через PDO и подготовленные запросы
- автозагрузка классов через Composer

## Требования

- PHP 8.1 или новее
- MySQL
- Composer
- расширения `pdo_mysql` и `mbstring`
- поддержка Argon2id в PHP

## Установка

1. Откройте папку проекта:

   ```bash
   cd C:\MAMP\htdocs\authSystem
   ```

2. Установите Composer autoload:

   ```bash
   composer install
   ```

3. Откройте phpMyAdmin и импортируйте файл:

   ```text
   database/authsystem.sql
   ```

   Файл создаст базу `authsystem` и таблицы:

   - `users`
   - `remember_tokens`

4. Скопируйте `.env.example` и переименуйте копию в `.env`.

5. При необходимости измените настройки в `.env`:

   ```env
   DB_HOST=localhost
   DB_PORT=3306
   DB_NAME=authsystem
   DB_USER=root
   DB_PASSWORD=root
   APP_BASE_PATH=/authSystem
   ```

6. Запустите Apache и MySQL в MAMP.

7. Откройте сайт:

   ```text
   http://localhost:8080/authSystem/public/index.php
   ```

   Если Apache работает на порту `80`:

   ```text
   http://localhost/authSystem/public/index.php
   ```

## Структура проекта

```text
authSystem/
├── app/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── ProfileController.php
│   │   └── RegisterController.php
│   ├── Core/
│   │   ├── Csrf.php
│   │   ├── Environment.php
│   │   ├── RememberToken.php
│   │   ├── Session.php
│   │   └── Validator.php
│   ├── Database/
│   │   └── Database.php
│   ├── Middleware/
│   │   └── AuthMiddleware.php
│   ├── Repositories/
│   │   ├── RememberTokenRepository.php
│   │   └── UserRepository.php
│   ├── Services/
│   │   ├── AuthService.php
│   │   ├── ProfileService.php
│   │   ├── RegisterService.php
│   │   └── RememberMeService.php
│   └── Views/
│       ├── auth/
│       │   ├── login.php
│       │   └── register.php
│       └── profile/
│           └── user_profile.php
├── database/
│   └── authsystem.sql
├── include/
│   └── header.php
├── public/
│   └── index.php
├── style/
│   └── css/
│       └── header.css
├── tests/
│   └── run.php
├── .env.example
├── .gitignore
├── composer.json
└── README.md
```

## Тесты

Для запуска тестов выполните:

```bash
composer test
```

Тесты проверяют:

- создание и разбор постоянного токена
- хеш токена
- проверку повторного пароля
- хеширование пароля через Argon2id
- Composer autoload

## Безопасность

- пароли не хранятся в открытом виде
- постоянный токен не хранится в базе в открытом виде
- cookie постоянного входа использует `HttpOnly` и `SameSite=Lax`
- при HTTPS cookie также получает параметр `Secure`
- все запросы к базе выполняются через подготовленные запросы PDO
- формы входа, регистрации, выхода и профиля защищены CSRF-токеном
- все сообщения и данные пользователя выводятся через `htmlspecialchars`
