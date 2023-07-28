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
    protected $registrationValidator;
    protected $loginValidator;
    protected $captchaConfig;

    public function __construct(
        UserGateway $gateway,
        RegistrationValidator $registrationValidator,
        LoginValidator $loginValidator,
        array $captchaConfig
    ) {
        $this->gateway = $gateway;
        $this->registrationValidator = $registrationValidator;
        $this->loginValidator = $loginValidator;
        $this->captchaConfig = $captchaConfig;
    }

    public function showRegistrationForm(): void
    {
        View::render('auth/register',  [
            'title' => 'Регистрация',
        ]);
    }

    public function register(): void
    {
        if (!Token::checkToken()) {
            Session::addErrors(['token' => 'Возникла ошибка! Пожалуйста, повторите отправку']);
            Route::back();
        };

        $userdata = [
            'name' => trim(strval($_POST['name'] ?? '')),
            'phone' => preg_replace('/[()\-_ ]/', '', strval($_POST['phone'] ?? '')),
            'email' => trim(strval($_POST['email'] ?? '')),
            'password' => trim(strval($_POST['password'] ?? '')),
            'confirmPassword' => trim(strval($_POST['confirm_password'] ?? '')),
        ];

        $errors = $this->registrationValidator->validate(...$userdata);

        if (array_filter($errors)) {
            Session::addInputValues($userdata);
            Session::addErrors($errors);
            Route::back();
        }

        $user = new User();
        $user->fill($userdata);
        $this->gateway->save($user);

        Session::addLogin($user->getEmail());
        Route::redirect('/profile');
    }

    public function showLoginForm(): void
    {
        View::render('auth/login', [
            'title' => 'Вход',
            'site_key' => $this->captchaConfig['site_key'],
        ]);
    }

    public function login(): void
    {
        if (!Token::checkToken()) {
            Session::addErrors(['token' => 'Возникла ошибка! Пожалуйста, повторите отправку']);
            Route::back();
        };

        if (!$this->checkCaptcha()) {
            Session::addErrors(['recaptcha' => 'Капча не пройдена']);
            Route::back();
        }

        $login = trim(strval($_POST['login'] ?? ''));
        $password = trim(strval($_POST['password'] ?? ''));

        $errors = $this->loginValidator->validate($login,  $password);

        if (array_filter($errors)) {
            Session::addInputValues(['login' => $login]);
            Session::addErrors($errors);
            Route::back();
        }

        $user = $this->gateway->findByLogin($login);

        if ($user && password_verify($password, $user->getPassword())) {
            Session::addLogin($login);
            Route::redirect('/profile');
        }

        Session::addErrors(['login' => 'Неверный логин или пароль']);
        Route::back();
    }

    public function logout(): void
    {
        if (!Token::checkToken()) {
            Session::addErrors(['token' => 'Возникла ошибка! Пожалуйста, повторите отправку']);
            Route::back();
        };

        Session::destroy();
        Route::redirect('/');
    }

    private function checkCaptcha(): bool
    {
        $recaptcha = $_POST['g-recaptcha-response'] ?? '';

        if (empty($recaptcha)) {
            return false;
        }

        $query = array(
            'secret' => $this->captchaConfig['secret_key'],
            'response' => $recaptcha,
            'remoteip' => $_SERVER['REMOTE_ADDR']
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
        $data = json_decode(curl_exec($ch), true);
        curl_close($ch);

        if (!$data['success']) {
            return false;
        }

        return true;
    }
}
