<?php

namespace Forms\DI;

use Forms\Database\Connection;
use Forms\Models\UserGateway;
use Forms\Controllers\AuthController;
use Forms\Controllers\ProfileController;
use Forms\Controllers\MainController;
use Forms\Validators\ProfileValidator;
use Forms\Validators\PasswordValidator;
use Forms\Validators\LoginValidator;
use Forms\Validators\RegistrationValidator;

class Container
{
    private $services = [];
    private $functions;

    public function __construct()
    {
        $this->functions = [
            'mysqlConfig' => function () {
                return (require __DIR__ . '/../../config/config.php')['mysql'];
            },
            'routes' => function () {
                return require __DIR__ . '/../../routes/routes.php';
            },
            Connection::class => function () {
                return new Connection($this->get('mysqlConfig'));
            },
            UserGateway::class => function () {
                return new UserGateway($this->get(Connection::class));
            },
            ProfileValidator::class => function () {
                return new ProfileValidator($this->get(UserGateway::class));
            },
            PasswordValidator::class => function () {
                return new PasswordValidator($this->get(UserGateway::class));
            },
            RegistrationValidator::class => function () {
                return new RegistrationValidator($this->get(UserGateway::class));
            },
            LoginValidator::class => function () {
                return new LoginValidator($this->get(UserGateway::class));
            },
            AuthController::class => function () {
                return new AuthController(
                    $this->get(UserGateway::class),
                );
            },
            ProfileController::class => function () {
                return new ProfileController(
                    $this->get(UserGateway::class),
                    $this->get(ProfileValidator::class),
                );
            },
            MainController::class => function () {
                return new MainController();
            },
        ];
    }

    public function has(string $id): bool
    {
        return isset($this->services[$id]);
    }

    public function get(string $id): mixed
    {
        if ($this->has($id)) {
            return $this->services[$id];
        }
        
        $service = $this->functions[$id]();
        $this->services[$id] = $service;
        return $service;
    }
}
