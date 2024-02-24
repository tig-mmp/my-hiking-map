<?php

namespace App\Dto;

use App\Utils\DtoUtils;
use DateTime;

class PointFormDto
{
    private ?int $id;
    private ?float $elevation;
    private ?float $latitude;
    private ?float $longitude;
    private ?DateTime $date;

    public function __construct(array $parameters)
    {
        $this->id = DtoUtils::getInt($parameters, "id");
        $this->elevation = DtoUtils::getFloat($parameters, "elevation");
        $this->latitude = DtoUtils::getFloat($parameters, "latitude");
        $this->longitude = DtoUtils::getFloat($parameters, "longitude");
        $this->date = DtoUtils::getDate($parameters, "date");
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getElevation(): ?float
    {
        return $this->elevation;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function getDate(): ?DateTime
    {
        return $this->date;
    }
}
