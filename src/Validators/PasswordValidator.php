<?php

namespace Forms\Validators;

use Forms\Interfaces\Validator;

class PasswordValidator implements Validator
{
    public function validate(array $data): array
    {
        $errors = [];

        $errors['newPassword'] = $this->validatePassword($data['password'], $data['currentPassword']);
        $errors['confirmPassword'] = $this->confirmPassword($data['password'], $data['confirmPassword']);

        return $errors;
    }

    private function validatePassword(string $password, string $currentPassword): ?string
    {
        if (empty($password)) {
            return 'Заполните это поле';
        }

        if ($password === $currentPassword) {
            return 'Текущий и новый пароли совпадают';
        }

        $passwordLength = mb_strlen($password);

        if ($passwordLength < 6) {
            return "Используйте не менее 6 символов (Вы использовали $passwordLength)";
        }

        if ($passwordLength > 255) {
            return "Используйте не более 255 символов (Вы использовали $passwordLength)";
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
