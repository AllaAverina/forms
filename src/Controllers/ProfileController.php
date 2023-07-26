<?php

namespace Forms\Controllers;

use Forms\Views\View;
use Forms\Helpers\Session;
use Forms\Helpers\Route;
use Forms\Helpers\Token;
use Forms\Models\UserGateway;
use Forms\Validators\ProfileValidator;
use Forms\Validators\PasswordValidator;

class ProfileController
{
    protected $gateway;

    public function __construct(UserGateway $gateway)
    {
        $this->gateway = $gateway;
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

    public function update(ProfileValidator $validator): void
    {
        if (!Token::checkToken()) {
            Session::setErrors(['token' => 'Возникла ошибка! Пожалуйста, повторите отправку']);
            Route::back();
        };

        $userdata = [
            'name' => trim(strval($_POST['name'] ?? '')),
            'phone' => preg_replace('/[()\-_ ]/', '', strval($_POST['phone'] ?? '')),
            'email' => trim(strval($_POST['email'] ?? '')),
        ];

        $user = $this->checkAuth();

        $errors = $validator->validate($userdata, $user->getId());

        if (array_filter($errors)) {
            Session::setErrors($errors);
            Route::back();
        }

        $user->fill($userdata);
        $this->gateway->save($user);

        Session::setLogin($user->getEmail());
        Session::setMessage('profile', 'Сохранено');

        Route::back();
    }

    public function updatePassword(PasswordValidator $validator): void
    {
        if (!Token::checkToken()) {
            Session::setErrors(['token' => 'Возникла ошибка! Пожалуйста, повторите отправку']);
            Route::back();
        };

        $currentPassword = trim(strval($_POST['current_password'] ?? ''));
        $password = trim(strval($_POST['password'] ?? ''));
        $confirmPassword = trim(strval($_POST['confirm_password'] ?? ''));

        $user = $this->checkAuth();

        if (!password_verify($currentPassword, $user->getPassword())) {
            Session::setErrors(['currentPassword' => 'Неверный пароль']);
            Route::back();
        }

        $errors = $validator->validate($currentPassword, $password, $confirmPassword);

        if (array_filter($errors)) {
            Session::setErrors($errors);
            Route::back();
        }

        $user->setPassword($password);
        $this->gateway->save($user);

        Session::setLogin($user->getEmail());
        Session::setMessage('password', 'Сохранено');

        Route::back();
    }

    public function destroy(): void
    {
        if (!Token::checkToken()) {
            Session::setErrors(['token' => 'Возникла ошибка! Пожалуйста, повторите отправку']);
            Route::back();
        };

        $user = $this->checkAuth();
        $password = trim(strval($_POST['password'] ?? ''));

        if (!password_verify($password, $user->getPassword())) {
            Session::setErrors(['password' => 'Неверный пароль']);
            Route::back();
        }

        $this->gateway->delete($user);

        Session::destroy();
        Route::redirect('/');
    }
}
