<?php

namespace App\Requests\Users\User;

use App\Utils\RequestUtils;

class UserRequest
{
    private $role;
    private $username;
    private $password;

    public function __construct(array $parameters)
    {
        $this->role = RequestUtils::getString($parameters, ("role"));
        $this->username = RequestUtils::getString($parameters, ("username"));
        $this->password = RequestUtils::getString($parameters, ("password"));
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
