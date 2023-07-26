<?php

namespace Forms\Database;

use PDO;

class Connection
{
    public $pdo;

    public function __construct(array $config)
    {
        $dsn = 'mysql:host=' . $config['host'] . ';dbname=' . $config['dbname'] . ';charset=utf8mb4;';
        $user = $config['user'];
        $password = $config['password'];
        $options = [
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_STRINGIFY_FETCHES => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ];

        $this->pdo = new PDO($dsn, $user, $password, $options);
    }

    public function runQuery(string $sql, array $params = null): object|false
    {
        if (!$params) {
            return $this->pdo->query($sql);
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function getLastInsertId(): int
    {
        return (int)$this->pdo->lastInsertId();
    }

    public function getRow(string $sql, string $class, array $params = null): object|false
    {
        return $this->runQuery($sql, $params)->fetchObject($class);
    }

    public function getColumn(string $sql, array $params = null): mixed
    {
        return $this->runQuery($sql, $params)->fetchColumn();
    }

    public function getRows(string $sql, string $class, array $params = null): array
    {
        return $this->runQuery($sql, $params)->fetchAll(PDO::FETCH_CLASS, $class);
    }
}
