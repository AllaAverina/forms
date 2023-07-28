<?php

namespace Forms\Validators;

use Forms\Models\UserGateway;

class RegistrationValidator
{
    protected $gateway;

    public function __construct(UserGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    public function validate(string $name, string $phone, string $email, string $password, string $confirmPassword): array
    {
        $errors = [];

        $errors['name'] = $this->validateName($name);
        $errors['phone'] = $this->validatePhone($phone);
        $errors['email'] = $this->validateEmail($email);
        $errors['password'] = $this->validatePassword($password);
        $errors['confirmPassword'] = $this->confirmPassword($password, $confirmPassword);

        return $errors;
    }

    private function validateName(string $input): ?string
    {
        if (empty($input)) {
            return 'Заполните это поле';
        }

        if (!preg_match("/^([а-яёa-z '\-]+)$/ui", $input)) {
            return 'Используйте только буквы, дефис, апостров и пробел';
        }

        $inputLength = mb_strlen($input);
        if ($inputLength > 255) {
            return "Используйте не более 255 символов (Вы использовали $inputLength)";
        }

        return null;
    }

    private function validatePhone(string $input): ?string
    {
        if (empty($input)) {
            return 'Заполните это поле';
        }

        if (!preg_match("/^([\d]{10})$/", $input)) {
            return 'Некорректное значение';
        }

        if ($this->gateway->checkPhone($input) > 0) {
            return 'Данный номер телефона уже используется';
        }

        return null;
    }

    private function validateEmail(string $input): ?string
    {
        if (empty($input)) {
            return 'Заполните это поле';
        }

        $inputLength = mb_strlen($input);
        if ($inputLength > 255) {
            return "Используйте не более 255 символов (Вы использовали $inputLength)";
        }

        if (!filter_var($input, FILTER_VALIDATE_EMAIL)) {
            return 'Некорректное значение';
        }

        if ($this->gateway->checkEmail($input) > 0) {
            return 'Данный email уже используется';
        }

        return null;
    }

    private function validatePassword(string $input): ?string
    {
        if (empty($input)) {
            return 'Заполните это поле';
        }

        $inputLength = mb_strlen($input);

        if ($inputLength < 6) {
            return "Используйте не менее 6 символов (Вы использовали $inputLength)";
        }

        if ($inputLength > 255) {
            return "Используйте не более 255 символов (Вы использовали $inputLength)";
        }

        return null;
    }

    private function confirmPassword(string $password, string $cofirmPassword): ?string
    {
        if (empty($cofirmPassword)) {
            return 'Заполните это поле';
        }

        if ($password !== $cofirmPassword) {
            return 'Пароли не совпадают';
        }

        return null;
    }
}
