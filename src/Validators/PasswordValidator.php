<?php

namespace Forms\Validators;

class PasswordValidator
{
    public function validate(string $currentPassword, string $password, string $confirmPassword): array
    {
        $errors = [];

        $errors['newPassword'] = $this->validatePassword($password, $currentPassword);
        $errors['confirmPassword'] = $this->confirmPassword($password, $confirmPassword);

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
