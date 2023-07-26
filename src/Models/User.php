<?php

namespace Forms\Models;

class User
{
    protected $id;
    protected $name;
    protected $phone;
    protected $email;
    protected $password;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    public function fill(array $data): void
    {
        $this->name = $data['name'];
        $this->phone = $data['phone'];
        $this->email = $data['email'];

        if (isset($data['password'])) {
            $this->password = password_hash($data['password'], PASSWORD_DEFAULT);
        }
    }
}
