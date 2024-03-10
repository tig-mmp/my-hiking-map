<?php

namespace App\Entity;

use App\Repository\LandmarkRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToOne;

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

    #[Column(nullable: true)]
    private ?int $pointId = null;

    #[ManyToOne(inversedBy: "landmarks")]
    private ?Point $point = null;

    #[Column(nullable: true)]
    private ?int $fileId = null;

    #[OneToOne(cascade: ["persist", "remove"])]
    private ?File $file = null;

    #[Column]
    private bool $isMoita = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function serializeList(): array
    {
        return ["id" => $this->id, "file" => $this->getFileSerialized()];
    }

    public function serializeForm(): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "file" => $this->getFileSerialized(),
            "point" => $this->getPointSerialized(),
            "landmarkTypeId" => $this->landmarkTypeId,
        ];
    }

    public function serializeMap(): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "file" => $this->getFileSerialized(),
            "point" => $this->getPointSerialized(),
            "landmarkTypeName" => $this->landmarkTypeId ? $this->landmarkType->getName() : null,
        ];
    }

    private function getFileSerialized(): ?array
    {
        return $this->fileId ? $this->file->serializeForm() : null;
    }

    private function getPointSerialized(): ?array
    {
        return $this->pointId ? $this->point->serializeForm() : null;
    }

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
        $this->landmarkTypeId = $landmarkType ? $landmarkType->getId() : null;
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
        $this->trackId = $track ? $track->getId() : null;
    }

    public function getPointId(): ?int
    {
        return $this->pointId;
    }

    public function setPointId(?int $pointId)
    {
        $this->pointId = $pointId;
    }

    public function getPoint(): ?Point
    {
        return $this->point;
    }

    public function setPoint(?Point $point)
    {
        $this->point = $point;
        $this->pointId = $point ? $point->getId() : null;
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

    public function isIsMoita(): bool
    {
        return $this->isMoita;
    }

    public function setIsMoita(bool $isMoita)
    {
        $this->isMoita = $isMoita;
    }
}
