<?php

use Forms\Controllers\AuthController;
use Forms\Controllers\ProfileController;
use Forms\Controllers\MainController;

return [
    'GET' => [
        '/register' => ['controller' => AuthController::class, 'action' => 'showRegistrationForm'],
        '/login' => ['controller' => AuthController::class, 'action' => 'showLoginForm'],
        '/profile' => ['controller' => ProfileController::class, 'action' => 'show'],
        '/profile/edit' => ['controller' => ProfileController::class, 'action' => 'edit'],
        '/' => ['controller' => MainController::class, 'action' => 'index'],
    ],
    'POST' => [
        '/register' => ['controller' => AuthController::class, 'action' => 'register', 'classes' => [Forms\Validators\RegistrationValidator::class]],
        '/login' => ['controller' => AuthController::class, 'action' => 'login', 'classes' => [Forms\Validators\LoginValidator::class]],
        '/logout' => ['controller' => AuthController::class, 'action' => 'logout'],
    ],
    'PUT' => [],
    'PATCH' => [
        '/profile/edit' => ['controller' => ProfileController::class, 'action' => 'update', 'classes' => [Forms\Validators\ProfileValidator::class]],
        '/profile/password/edit' => ['controller' => ProfileController::class, 'action' => 'updatePassword', 'classes' => [Forms\Validators\PasswordValidator::class]],
    ],
    'DELETE' => [
        '/profile/delete' => ['controller' => ProfileController::class, 'action' => 'destroy'],
    ],
];
