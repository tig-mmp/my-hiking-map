<?php

namespace App\Entity;

use App\Entity\Landmark;
use App\Entity\Point;
use App\Entity\TrackLocation;
use App\Repository\TrackRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;

#[Entity(repositoryClass: TrackRepository::class)]
class Track
{
    #[Id]
    #[GeneratedValue]
    #[Column]
    private ?int $id = null;

    #[Column(nullable: true)]
    private ?int $startLocationId = null;

    #[ManyToOne(inversedBy: "tracks")]
    private ?Location $startLocation = null;

    #[Column(length: 255)]
    private ?string $name = null;

    #[Column(length: 1000, nullable: true)]
    private ?string $description = null;

    #[Column(nullable: true)]
    private ?float $distance = null;

    #[Column(nullable: true)]
    private ?float $slope = null;

    #[Column(length: 20, nullable: true)]
    private ?string $routeCode = null;

    #[Column(type: Types::SMALLINT, nullable: true)]
    private ?int $difficulty = null;

    #[Column(type: Types::SMALLINT, nullable: true)]
    private ?int $landscape = null;

    #[Column(type: Types::SMALLINT, nullable: true)]
    private ?int $enjoyment = null;

    #[Column(length: 100, nullable: true)]
    private ?string $trackUrl = null;

    #[Column(length: 100, nullable: true)]
    private ?string $officialUrl = null;

    #[Column(length: 100, nullable: true)]
    private ?string $groupName = null;

    #[Column(length: 100, nullable: true)]
    private ?string $guide = null;

    #[Column(type: Types::SMALLINT, nullable: true)]
    private ?int $weekNumber = null;

    #[Column]
    private bool $isMoita = false;

    #[Column(length: 6, nullable: true)]
    private ?string $duration = null;

    #[Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?DateTimeInterface $date = null;

    #[Column(length: 6, nullable: true)]
    private ?string $startTime = null;

    #[Column(length: 6, nullable: true)]
    private ?string $endTime = null;

    #[Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $createdAt = null;

    #[Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $updatedAt = null;

    #[Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $deletedAt = null;

    #[OneToMany(mappedBy: "track", targetEntity: TrackLocation::class)]
    private Collection $trackLocations;

    #[OneToMany(mappedBy: "track", targetEntity: Landmark::class)]
    private Collection $landmarks;

    #[OneToMany(mappedBy: "track", targetEntity: Point::class)]
    private Collection $points;

    #[Column(nullable: true)]
    private ?int $fileId = null;

    #[OneToOne(cascade: ["persist", "remove"])]
    private ?File $file = null;

    public function __construct()
    {
        $this->trackLocations = new ArrayCollection();
        $this->landmarks = new ArrayCollection();
        $this->points = new ArrayCollection();
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
    }

    public function serializeList(): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
        ];
    }

    public function serializeForm(): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "startLocationId" => $this->startLocationId,
            "startCountyId" => $this->startLocationId ? $this->startLocation->getCountyId() : null,
            "startDistrictId" => $this->startLocationId && $this->startLocation->getCountyId() ? $this->startLocation->getCounty()->getDistrictId() : null,
            "description" => $this->description,
            "distance" => $this->distance,
            "slope" => $this->slope,
            "routeCode" => $this->routeCode,
            "difficulty" => $this->difficulty,
            "landscape" => $this->landscape,
            "enjoyment" => $this->enjoyment,
            "trackUrl" => $this->trackUrl,
            "officialUrl" => $this->officialUrl,
            "groupName" => $this->groupName,
            "guide" => $this->guide,
            "weekNumber" => $this->weekNumber,
            "isMoita" => $this->isMoita,
            "duration" => $this->duration,
            "date" => $this->date ? $this->date->format("Y-m-d") : null,
            "startTime" => $this->startTime,
            "endTime" => $this->endTime,
            "landmarks" => $this->getLandmarksSerialized(),
        ];
    }

    public function serializeMap(): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "description" => $this->description,
            "date" => $this->date ? $this->date->format("Y-m-d") : null,
            "points" => $this->getPointCoordinates(),
        ];
    }

    private function getPointCoordinates(): array
    {
        $points = [];
        foreach ($this->points as $point) {
            $points[] = [$point->getLongitude(), $point->getLatitude()];
        }
        return $points;
    }

    private function getLandmarksSerialized(): array
    {
        $landmarks = [];
        foreach ($this->landmarks as $landmark) {
            $landmarks[] = $landmark->serializeForm();
        }
        return $landmarks;
    }

    public function getLandmarkIds(): array
    {
        $landmarkIds = [];
        foreach ($this->landmarks as $landmark) {
            $landmarkIds[] = $landmark->getId();
        }
        return $landmarkIds;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartLocationId(): ?int
    {
        return $this->startLocationId;
    }

    public function setStartLocationId(?int $startLocationId)
    {
        $this->startLocationId = $startLocationId;
    }

    public function getStartLocation(): ?Location
    {
        return $this->startLocation;
    }

    public function setStartLocation(?Location $startLocation)
    {
        $this->startLocation = $startLocation;
        $this->startLocationId = $startLocation ? $startLocation->getId() : null;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description)
    {
        $this->description = $description;
    }

    public function getDistance(): ?float
    {
        return $this->distance;
    }

    public function setDistance(?float $distance)
    {
        $this->distance = $distance;
    }

    public function getSlope(): ?float
    {
        return $this->slope;
    }

    public function setSlope(?float $slope)
    {
        $this->slope = $slope;
    }

    public function getRouteCode(): ?string
    {
        return $this->routeCode;
    }

    public function setRouteCode(?string $routeCode)
    {
        $this->routeCode = $routeCode;
    }

    public function getDifficulty(): ?int
    {
        return $this->difficulty;
    }

    public function setDifficulty(?int $difficulty)
    {
        $this->difficulty = $difficulty;
    }

    public function getLandscape(): ?int
    {
        return $this->landscape;
    }

    public function setLandscape(?int $landscape)
    {
        $this->landscape = $landscape;
    }

    public function getEnjoyment(): ?int
    {
        return $this->enjoyment;
    }

    public function setEnjoyment(?int $enjoyment)
    {
        $this->enjoyment = $enjoyment;
    }

    public function getTrackUrl(): ?string
    {
        return $this->trackUrl;
    }

    public function setTrackUrl(?string $trackUrl)
    {
        $this->trackUrl = $trackUrl;
    }

    public function getOfficialUrl(): ?string
    {
        return $this->officialUrl;
    }

    public function setOfficialUrl(?string $officialUrl)
    {
        $this->officialUrl = $officialUrl;
    }

    public function getGroupName(): ?string
    {
        return $this->groupName;
    }

    public function setGroupName(?string $groupName)
    {
        $this->groupName = $groupName;
    }

    public function getGuide(): ?string
    {
        return $this->guide;
    }

    public function setGuide(?string $guide)
    {
        $this->guide = $guide;
    }

    public function getWeekNumber(): ?int
    {
        return $this->weekNumber;
    }

    public function setWeekNumber(?int $weekNumber)
    {
        $this->weekNumber = $weekNumber;
    }

    public function getIsMoita(): bool
    {
        return $this->isMoita;
    }

    public function setIsMoita(bool $isMoita)
    {
        $this->isMoita = $isMoita;
    }

    public function getDuration(): ?string
    {
        return $this->duration;
    }

    public function setDuration(?string $duration)
    {
        $this->duration = $duration;
    }

    public function getDate(): ?DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?DateTimeInterface $date)
    {
        $this->date = $date;
    }

    public function getStartTime(): ?string
    {
        return $this->startTime;
    }

    public function setStartTime(?string $startTime)
    {
        $this->startTime = $startTime;
    }

    public function getEndTime(): ?string
    {
        return $this->endTime;
    }

    public function setEndTime(?string $endTime)
    {
        $this->endTime = $endTime;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeInterface $updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    public function getDeletedAt(): ?DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?DateTimeInterface $deletedAt)
    {
        $this->deletedAt = $deletedAt;
    }

    /**
     * @return Collection<int, TrackLocation>
     */
    public function getTrackLocations(): Collection
    {
        return $this->trackLocations;
    }

    public function addTrackLocation(TrackLocation $trackLocation)
    {
        if (!$this->trackLocations->contains($trackLocation)) {
            $this->trackLocations->add($trackLocation);
            $trackLocation->setTrack($this);
        }
    }

    public function removeTrackLocation(TrackLocation $trackLocation)
    {
        if ($this->trackLocations->removeElement($trackLocation)) {
            if ($trackLocation->getTrack() === $this) {
                $trackLocation->setTrack(null);
            }
        }
    }

    /**
     * @return Collection<int, Landmark>
     */
    public function getLandmarks(): Collection
    {
        return $this->landmarks;
    }

    public function addLandmark(Landmark $landmark)
    {
        if (!$this->landmarks->contains($landmark)) {
            $this->landmarks->add($landmark);
            $landmark->setTrack($this);
        }
    }

    public function removeLandmark(Landmark $landmark)
    {
        if ($this->landmarks->removeElement($landmark)) {
            if ($landmark->getTrack() === $this) {
                $landmark->setTrack(null);
            }
        }
    }

    /**
     * @return Collection<int, Point>
     */
    public function getPoints(): Collection
    {
        return $this->points;
    }

    public function addPoint(Point $point)
    {
        if (!$this->points->contains($point)) {
            $this->points->add($point);
            $point->setTrack($this);
        }
    }

    public function removePoint(Point $point)
    {
        if ($this->points->removeElement($point)) {
            if ($point->getTrack() === $this) {
                $point->setTrack(null);
            }
        }
    }

    public function getFileId(): ?int
    {
        return $this->fileId;
    }

    public function setFileId(?int $fileId)
    {
        $this->fileId = $fileId;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(?File $file)
    {
        $this->file = $file;
        $this->fileId = $file ? $file->getId() : null;
    }
}
