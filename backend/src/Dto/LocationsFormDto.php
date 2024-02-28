<?php

namespace App\Dto;

use App\Utils\DtoUtils;

class LocationsFormDto
{
    private ?int $districtId;
    private ?string $districtName;
    private ?int $countyId;
    private ?string $countyName;
    private ?int $locationId;
    private ?string $locationName;

    public function __construct(array $parameters, bool $isStart = false)
    {
        $this->districtId = DtoUtils::getInt($parameters, $isStart ? "startDistrictId" : "districtId");
        $this->districtName = !$this->districtId ? DtoUtils::getString($parameters, $isStart ? "startDistrictId" : "districtId") : null;
        $this->countyId = DtoUtils::getInt($parameters, $isStart ? "startCountyId" : "countyId");
        $this->countyName = !$this->countyId ? DtoUtils::getString($parameters, $isStart ? "startCountyId" : "countyId") : null;
        $this->locationId = DtoUtils::getInt($parameters, $isStart ? "startLocationId" : "locationId");
        $this->locationName = !$this->locationId ? DtoUtils::getString($parameters, $isStart ? "startLocationId" : "locationId") : null;
    }

    public function getDistrictId(): ?int
    {
        return $this->districtId;
    }

    public function getDistrictName(): ?string
    {
        return $this->districtName;
    }

    public function getCountyId(): ?int
    {
        return $this->countyId;
    }

    public function getCountyName(): ?string
    {
        return $this->countyName;
    }

    public function getLocationId(): ?int
    {
        return $this->locationId;
    }

    public function getLocationName(): ?string
    {
        return $this->locationName;
    }
}
