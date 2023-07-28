<?php

namespace Forms\Validators;

use Forms\Models\UserGateway;

class ProfileValidator
{
    protected $gateway;

    public function __construct(UserGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    public function validate(int $id, string $name, string $phone, string $email): array
    {
        $errors = [];

        $errors['name'] = $this->validateName($name);
        $errors['phone'] = $this->validatePhone($phone, $id);
        $errors['email'] = $this->validateEmail($email, $id);

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

    private function validatePhone(string $input, int $id): ?string
    {
        if (empty($input)) {
            return 'Заполните это поле';
        }

        if (!preg_match("/^([\d]{10})$/", $input)) {
            return 'Некорректное значение';
        }

        if ($this->gateway->checkPhone($input, $id) > 0) {
            return 'Данный номер телефона уже используется';
        }

        return null;
    }

    private function validateEmail(string $input, int $id): ?string
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

        if ($this->gateway->checkEmail($input, $id) > 0) {
            return 'Данный email уже используется';
        }

        return null;
    }
}
