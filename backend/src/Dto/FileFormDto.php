<?php

namespace App\Dto;

use App\Utils\DtoUtils;

class FileFormDto
{
    private ?int $id;
    private ?string $name;
    private ?string $url;

    public function __construct(array $parameters)
    {
        $this->id = DtoUtils::getInt($parameters, "id");
        $this->name = DtoUtils::getString($parameters, "name");
        $this->url = DtoUtils::getString($parameters, "url");
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }
}
