<?php

namespace Forms\Models;

use Forms\Models\User;
use Forms\Database\Connection;

class UserGateway
{
    protected $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function findById(int $id): ?self
    {
        return $this->connection->getRow(
            'SELECT * FROM `users` WHERE `id`=:id',
            User::class,
            [':id' => $id]
        );
    }

    public function findByLogin(string $login): object|false
    {
        return $this->connection->getRow(
            'SELECT * FROM `users` WHERE `phone`=:phone OR `email`=:email',
            User::class,
            [':phone' => $login, ':email' => $login]
        );
    }

    public function save(User $user): void
    {
        ($user->getId()) ? $this->update($user) : $this->insert($user);
    }

    public function insert(User $user): void
    {
        $this->connection->runQuery(
            'INSERT INTO `users` (`name`, `phone`, `email`, `password`) VALUES (:name, :phone, :email, :password)',
            [
                ':name' => $user->getName(),
                ':phone' => $user->getPhone(),
                ':email' => $user->getEmail(),
                ':password' => $user->getPassword(),
            ]
        );
    }

    public function update(User $user): void
    {
        $this->connection->runQuery(
            'UPDATE `users` SET `name`=:name, `phone`=:phone, `email`=:email, `password`=:password WHERE `id`=:id',
            [
                ':id' => $user->getId(),
                ':name' => $user->getName(),
                ':phone' => $user->getPhone(),
                ':email' => $user->getEmail(),
                ':password' => $user->getPassword(),
            ]
        );
    }

    public function delete(User $user): void
    {
        $this->connection->runQuery(
            'DELETE FROM `users` WHERE `id`=:id',
            [':id' => $user->getId()]
        );
    }

    public function checkEmail(string $email, int $id = null): int
    {
        if ($id) {
            return (int)$this->connection->getColumn(
                "SELECT COUNT(*) FROM `users` WHERE `email`=:email AND NOT `id`=:id",
                [':email' => $email, ':id' => $id]
            );
        }

        return (int)$this->connection->getColumn(
            "SELECT COUNT(*) FROM `users` WHERE `email`=:email",
            [':email' => $email]
        );
    }

    public function checkPhone(string $phone, int $id = null): int
    {
        if ($id) {
            return (int)$this->connection->getColumn(
                "SELECT COUNT(*) FROM `users` WHERE `phone`=:phone AND NOT `id`=:id",
                [':phone' => $phone, ':id' => $id]
            );
        }

        return (int)$this->connection->getColumn(
            "SELECT COUNT(*) FROM `users` WHERE `phone`=:phone",
            [':phone' => $phone]
        );
    }
}
