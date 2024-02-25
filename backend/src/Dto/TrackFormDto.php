<?php

namespace App\Dto;

use App\Utils\DtoUtils;
use DateTime;

class TrackFormDto
{
    private ?int $id;
    private ?string $name;
    private ?string $url;
    private LocationsFormDto $startLocationDto;
    private ?string $description;
    private ?float $distance;
    private ?float $slope;
    private ?string $routeCode;
    private ?int $difficulty;
    private ?int $landscape;
    private ?int $enjoyment;
    private ?string $trackUrl;
    private ?string $officialUrl;
    private ?string $groupName;
    private ?string $guide;
    private ?int $weekNumber;
    private bool $isMoita;
    private ?string $duration;
    private ?DateTime $date;
    private ?string $startTime;
    private ?string $endTime;
    private array $landmarks;
    private ?FileFormDto $file;
    private array $points;

    public function __construct(array $parameters)
    {
        $this->id = DtoUtils::getInt($parameters, "id");
        $this->name = DtoUtils::getString($parameters, "name");
        $this->url = DtoUtils::getString($parameters, "url");
        $this->startLocationDto = new LocationsFormDto($parameters, true);
        $this->description = DtoUtils::getString($parameters, "description");
        $this->distance = DtoUtils::getFloat($parameters, "distance");
        $this->slope = DtoUtils::getFloat($parameters, "slope");
        $this->routeCode = DtoUtils::getString($parameters, "routeCode");
        $this->difficulty = DtoUtils::getInt($parameters, "difficulty");
        $this->landscape = DtoUtils::getInt($parameters, "landscape");
        $this->enjoyment = DtoUtils::getInt($parameters, "enjoyment");
        $this->trackUrl = DtoUtils::getString($parameters, "trackUrl");
        $this->officialUrl = DtoUtils::getString($parameters, "officialUrl");
        $this->groupName = DtoUtils::getString($parameters, "groupName");
        $this->guide = DtoUtils::getString($parameters, "guide");
        $this->weekNumber = DtoUtils::getInt($parameters, "weekNumber");
        $this->isMoita = DtoUtils::getBoolFalse($parameters, "isMoita");
        $this->duration = DtoUtils::getString($parameters, "duration");
        $this->date = DtoUtils::getDate($parameters, "date");
        $this->startTime = DtoUtils::getString($parameters, "startTime");
        $this->endTime = DtoUtils::getString($parameters, "endTime");
        $this->landmarks = DtoUtils::getArray($parameters, "landmarks");
        $file = DtoUtils::getArrayOrNull($parameters, "file");
        $this->file = $file ? new FileFormDto($file) : null;
        $this->points = DtoUtils::getArray($parameters, "points");
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

    public function getStartLocationDto(): LocationsFormDto
    {
        return $this->startLocationDto;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getDistance(): ?float
    {
        return $this->distance;
    }

    public function getSlope(): ?float
    {
        return $this->slope;
    }

    public function getRouteCode(): ?string
    {
        return $this->routeCode;
    }

    public function getDifficulty(): ?int
    {
        return $this->difficulty;
    }

    public function getLandscape(): ?int
    {
        return $this->landscape;
    }

    public function getEnjoyment(): ?int
    {
        return $this->enjoyment;
    }

    public function getTrackUrl(): ?string
    {
        return $this->trackUrl;
    }

    public function getOfficialUrl(): ?string
    {
        return $this->officialUrl;
    }

    public function getGroupName(): ?string
    {
        return $this->groupName;
    }

    public function getGuide(): ?string
    {
        return $this->guide;
    }

    public function getWeekNumber(): ?int
    {
        return $this->weekNumber;
    }

    public function getIsMoita(): bool
    {
        return $this->isMoita;
    }

    public function getDuration(): ?string
    {
        return $this->duration;
    }

    public function getDate(): ?DateTime
    {
        return $this->date;
    }

    public function getStartTime(): ?string
    {
        return $this->startTime;
    }

    public function getEndTime(): ?string
    {
        return $this->endTime;
    }

    public function getLandmarks(): ?array
    {
        return $this->landmarks;
    }

    public function getFile(): ?FileFormDto
    {
        return $this->file;
    }

    public function getPoints(): ?array
    {
        return $this->points;
    }
}
