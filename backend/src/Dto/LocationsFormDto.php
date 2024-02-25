<?php

namespace App\Dto;

use App\Utils\DtoUtils;

class LocationsFormDto
{
    private ?int $districtId = null;
    private ?string $districtName = null;
    private ?int $countyId = null;
    private ?string $countyName = null;
    private ?int $locationId = null;
    private ?string $locationName = null;

    public function __construct(array $parameters, bool $isStart = false)
    {
        $this->districtId = DtoUtils::getInt($parameters, $isStart ? "startDistrictId" : "districtId");
        if (!$this->districtId) {
            $this->districtName = DtoUtils::getString($parameters, $isStart ? "startDistrictId" : "districtId");
        }
        $this->countyId = DtoUtils::getInt($parameters, $isStart ? "startCountyId" : "countyId");
        if (!$this->countyId) {
            $this->countyName = DtoUtils::getString($parameters, $isStart ? "startCountyId" : "countyId");
        }
        $this->locationId = DtoUtils::getInt($parameters, $isStart ? "startLocationId" : "locationId");
        if (!$this->locationId) {
            $this->locationName = DtoUtils::getString($parameters, $isStart ? "startLocationId" : "locationId");
        }
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
