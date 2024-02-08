<?php

namespace App\Entity;

use App\Repository\LandmarkRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

#[Entity(repositoryClass: LandmarkRepository::class)]
class Landmark
{
    #[Id]
    #[GeneratedValue]
    #[Column]
    private ?int $id = null;

    #[Column(length: 100)]
    private ?string $name = null;

    #[Column]
    private ?int $landmarkTypeId = null;

    #[ManyToOne(inversedBy: "landmarks")]
    #[JoinColumn(nullable: false)]
    private ?LandmarkType $landmarkType = null;

    #[Column]
    private ?int $trackId = null;

    #[ManyToOne(inversedBy: "landmarks")]
    #[JoinColumn(nullable: false)]
    private ?Track $track = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getLandmarkTypeId(): ?int
    {
        return $this->landmarkTypeId;
    }

    public function setLandmarkTypeId(int $landmarkTypeId)
    {
        $this->landmarkTypeId = $landmarkTypeId;
    }

    public function getLandmarkType(): ?LandmarkType
    {
        return $this->landmarkType;
    }

    public function setLandmarkType(?LandmarkType $landmarkType)
    {
        $this->landmarkType = $landmarkType;
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
