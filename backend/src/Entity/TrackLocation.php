<?php

namespace App\Entity;

use App\Repository\TrackLocationRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

#[Entity(repositoryClass: TrackLocationRepository::class)]
class TrackLocation
{
    #[Id]
    #[GeneratedValue]
    #[Column]
    private ?int $id = null;

    #[Column]
    private ?int $locationId = null;

    #[ManyToOne(inversedBy: "trackLocations")]
    #[JoinColumn(nullable: false)]
    private ?Location $location = null;

    #[Column]
    private ?int $trackId = null;

    #[ManyToOne(inversedBy: "trackLocations")]
    #[JoinColumn(nullable: false)]
    private ?Track $track = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLocationId(): ?int
    {
        return $this->locationId;
    }

    public function setLocationId(int $locationId)
    {
        $this->locationId = $locationId;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location)
    {
        $this->location = $location;
    }

    public function getTrackId(): ?int
    {
        return $this->trackId;
    }

    public function setTrackId(int $trackId)
    {
        $this->trackId = $trackId;
    }

    public function getTrack(): ?Track
    {
        return $this->track;
    }

    public function setTrack(?Track $track)
    {
        $this->track = $track;
    }
}
