<?php

namespace App\Dto;

use App\Utils\DtoUtils;

class LandmarkFormDto
{
    private ?string $id;
    private ?string $name;
    private ?PointFormDto $point;

    public function __construct(array $parameters)
    {
        $this->id = DtoUtils::getInt($parameters, "id");
        $this->name = DtoUtils::getString($parameters, "name");
        $point = DtoUtils::getArrayOrNull($parameters, "point");
        $this->point = $point ? new PointFormDto($point) : null;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getPoint(): ?PointFormDto
    {
        return $this->point;
    }
}
