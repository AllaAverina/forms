<?php

namespace Forms\Validators;

class LoginValidator
{
    public function validate(string $login, string $password): array
    {
        $errors = [];

        $errors['login'] = $this->validateLogin($login);
        $errors['password'] = $this->validatePassword($password);

        return $errors;
    }

    private function validateLogin(string $input): ?string
    {
        if (empty($input)) {
            return 'Заполните это поле';
        }

        if (!(filter_var($input, FILTER_VALIDATE_EMAIL) || preg_match("/^[\d]{10}$/ui", $input))) {
            return 'Некорректное значение';
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
}
