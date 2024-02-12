<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[Entity(repositoryClass: UserRepository::class)]
class User implements PasswordAuthenticatedUserInterface
{
    #[Id]
    #[GeneratedValue]
    #[Column]
    private ?int $id = null;

    #[Column(length: 100)]
    private ?string $username = null;

    #[Column(length: 100)]
    private ?string $password = null;

    #[Column(length: 100)]
    private ?string $role = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function serializeList(): array
    {
        return ["id" => $this->id, "username" => $this->username, "role" => $this->role];
    }

    public function serializeForm(): array
    {
        return ["username" => $this->username, "role" => $this->role];
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username)
    {
        $this->username = $username;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role)
    {
        $this->role = $role;
    }
}
