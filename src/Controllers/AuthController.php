<?php

namespace Forms\Controllers;

use Forms\Views\View;
use Forms\Helpers\Session;
use Forms\Helpers\Route;
use Forms\Helpers\Token;
use Forms\Models\User;
use Forms\Models\UserGateway;
use Forms\Validators\RegistrationValidator;
use Forms\Validators\LoginValidator;

class AuthController
{
    protected $gateway;

    public function __construct(UserGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    public function showRegistrationForm(): void
    {
        View::render('auth/register',  [
            'title' => 'Регистрация',
        ]);
    }

    public function register(RegistrationValidator $validator): void
    {
        if (!Token::checkToken()) {
            Session::setErrors(['token' => 'Возникла ошибка! Пожалуйста, повторите отправку']);
            Route::back();
        };

        $userdata = [
            'name' => trim(strval($_POST['name'] ?? '')),
            'phone' => preg_replace('/[()\-_ ]/', '', strval($_POST['phone'] ?? '')),
            'email' => trim(strval($_POST['email'] ?? '')),
            'password' => trim(strval($_POST['password'] ?? '')),
            'confirmPassword' => trim(strval($_POST['confirm_password'] ?? '')),
        ];

        $errors = $validator->validate($userdata);

        if (array_filter($errors)) {
            Session::setInputValues($userdata);
            Session::setErrors($errors);
            Route::back();
        }

        $user = new User();
        $user->fill($userdata);
        $this->gateway->save($user);

        Session::setLogin($user->getEmail());
        Route::redirect('/profile');
    }

    public function showLoginForm(): void
    {
        View::render('auth/login', [
            'title' => 'Вход',
        ]);
    }

    public function login(LoginValidator $validator): void
    {
        if (!Token::checkToken()) {
            Session::setErrors(['token' => 'Возникла ошибка! Пожалуйста, повторите отправку']);
            Route::back();
        };

        $this->checkCaptcha();

        $login = trim(strval($_POST['login'] ?? ''));
        $password = trim(strval($_POST['password'] ?? ''));

        $errors = $validator->validate($login, $password);

        if (array_filter($errors)) {
            Session::setInputValues(['login' => $login]);
            Session::setErrors($errors);
            Route::back();
        }

        $user = $this->gateway->findByLogin($login);

        if ($user && password_verify($password, $user->getPassword())) {
            Session::setLogin($login);
            Route::redirect('/profile');
        }

        Session::setErrors(['login' => 'Неверный логин или пароль']);
        Route::back();
    }

    public function logout(): void
    {
        if (!Token::checkToken()) {
            Session::setErrors(['token' => 'Возникла ошибка! Пожалуйста, повторите отправку']);
            Route::back();
        };

        Session::destroy();
        Route::redirect('/');
    }

    private function checkCaptcha(): void
    {
        $recaptcha = $_POST['g-recaptcha-response'] ?? '';

        if (empty($recaptcha)) {
            Session::setErrors(['recaptcha' => 'Капча не пройдена']);
            Route::back();
        }

        $query = array(
            'secret' => '6Lc-KFYnAAAAAPdSAKbP26-3nydrMuZHtv9L09iw',
            'response' => $recaptcha,
            'remoteip' => $_SERVER['REMOTE_ADDR']
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
        $data = json_decode(curl_exec($ch), $assoc = true);
        curl_close($ch);

        if (!$data['success']) {
            Session::setErrors(['recaptcha' => 'Капча не пройдена']);
            Route::back();
        }
    }
}
