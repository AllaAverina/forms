<?php

namespace Forms\Controllers;

use Forms\Views\View;
use Forms\Helpers\Session;
use Forms\Helpers\Route;
use Forms\Helpers\Token;
use Forms\Models\UserGateway;
use Forms\Interfaces\Validator;

class ProfileController
{
    protected $gateway;
    protected $profileValidator;
    protected $passwordValidator;

    public function __construct(
        UserGateway $gateway,
        Validator $profileValidator,
        Validator $passwordValidator,
    ) {
        $this->gateway = $gateway;
        $this->profileValidator = $profileValidator;
        $this->passwordValidator = $passwordValidator;
    }

    private function checkAuth(): object
    {
        $login = Session::getLogin();

        if ($login) {
            $user = $this->gateway->findByLogin($login);

            if ($user) {
                return $user;
            }
        }

        Route::redirect('/');
    }

    public function show(): void
    {
        $user = $this->checkAuth();

        View::render('profile/show', [
            'title' => 'Профиль',
            'user' => $user,
        ]);
    }

    public function edit(): void
    {
        $user = $this->checkAuth();

        View::render('profile/edit', [
            'title' => 'Профиль',
            'user' => $user,
        ]);
    }

    public function update(): void
    {
        if (!Token::checkToken()) {
            Session::addErrors(['token' => 'Возникла ошибка! Пожалуйста, повторите отправку']);
            Route::back();
        };

        $user = $this->checkAuth();

        $userdata = [
            'id' => $user->getId(),
            'name' => trim(strval($_POST['name'] ?? '')),
            'phone' => preg_replace('/[()\-_ ]/', '', strval($_POST['phone'] ?? '')),
            'email' => trim(strval($_POST['email'] ?? '')),
        ];

        $errors = $this->profileValidator->validate($userdata);

        if (array_filter($errors)) {
            Session::addErrors($errors);
            Route::back();
        }

        $user->fill($userdata);
        $this->gateway->save($user);

        Session::addLogin($user->getEmail());
        Session::addMessage('profile', 'Сохранено');

        Route::back();
    }

    public function updatePassword(): void
    {
        if (!Token::checkToken()) {
            Session::addErrors(['token' => 'Возникла ошибка! Пожалуйста, повторите отправку']);
            Route::back();
        };

        $currentPassword = trim(strval($_POST['current_password'] ?? ''));
        $password = trim(strval($_POST['password'] ?? ''));
        $confirmPassword = trim(strval($_POST['confirm_password'] ?? ''));

        $user = $this->checkAuth();

        if (!password_verify($currentPassword, $user->getPassword())) {
            Session::addErrors(['currentPassword' => 'Неверный пароль']);
            Route::back();
        }

        $errors = $this->passwordValidator->validate([$currentPassword, $password, $confirmPassword]);

        if (array_filter($errors)) {
            Session::addErrors($errors);
            Route::back();
        }

        $user->setPassword($password);
        $this->gateway->save($user);

        Session::addLogin($user->getEmail());
        Session::addMessage('password', 'Сохранено');

        Route::back();
    }

    public function destroy(): void
    {
        if (!Token::checkToken()) {
            Session::addErrors(['token' => 'Возникла ошибка! Пожалуйста, повторите отправку']);
            Route::back();
        };

        $user = $this->checkAuth();
        $password = trim(strval($_POST['password'] ?? ''));

        if (!password_verify($password, $user->getPassword())) {
            Session::addErrors(['password' => 'Неверный пароль']);
            Route::back();
        }

        $this->gateway->delete($user);

        Session::destroy();
        Route::redirect('/');
    }
}
