<?php

namespace App\Dto;

use App\Utils\DtoUtils;

class UserFormDto
{
    private ?string $role;
    private ?string $username;
    private ?string $password;

    public function __construct(array $parameters)
    {
        $this->role = DtoUtils::getString($parameters, ("role"));
        $this->username = DtoUtils::getString($parameters, ("username"));
        $this->password = DtoUtils::getString($parameters, ("password"));
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }
}
