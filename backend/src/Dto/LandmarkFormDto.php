<?php

namespace App\Dto;

use App\Dto\FileFormDto;
use App\Utils\DtoUtils;

class LandmarkFormDto
{
    private ?string $id;
    private ?string $name;
    private ?PointFormDto $point;
    private ?int $landmarkTypeId;
    private ?string $landmarkTypeName;
    private ?FileFormDto $file;

    public function __construct(array $parameters)
    {
        $this->id = DtoUtils::getInt($parameters, "id");
        $this->name = DtoUtils::getString($parameters, "name");
        $point = DtoUtils::getArrayOrNull($parameters, "point");
        $this->point = $point ? new PointFormDto($point) : null;
        $this->landmarkTypeId = DtoUtils::getInt($parameters, "landmarkTypeId");
        if (!$this->landmarkTypeId) {
            $this->landmarkTypeName = DtoUtils::getString($parameters, "landmarkTypeId");
        }
        $file = DtoUtils::getArrayOrNull($parameters, "file");
        $this->file = $file ? new FileFormDto($file) : null;
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

    public function getLandmarkTypeId(): ?int
    {
        return $this->landmarkTypeId;
    }

    public function getLandmarkTypeName(): ?string
    {
        return $this->landmarkTypeName;
    }

    public function getFile(): ?FileFormDto
    {
        return $this->file;
    }
}
