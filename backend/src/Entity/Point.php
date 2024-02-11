<?php

namespace App\Entity;

use App\Entity\Landmark;
use App\Repository\PointRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;

#[Entity(repositoryClass: PointRepository::class)]
class Point
{
    #[Id]
    #[GeneratedValue]
    #[Column]
    private ?int $id = null;

    #[Column]
    private ?int $trackId = null;

    #[ManyToOne(inversedBy: "points")]
    #[JoinColumn(nullable: false)]
    private ?Track $track = null;

    #[Column]
    private ?float $elevation = null;

    #[Column]
    private ?float $latitude = null;

    #[Column]
    private ?float $longitude = null;

    #[Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $date = null;

    #[OneToMany(mappedBy: "point", targetEntity: Landmark::class)]
    private Collection $landmarks;

    public function __construct()
    {
        $this->landmarks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getElevation(): ?float
    {
        return $this->elevation;
    }

    public function setElevation(float $elevation)
    {
        $this->elevation = $elevation;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude)
    {
        $this->latitude = $latitude;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude)
    {
        $this->longitude = $longitude;
    }

    public function getDate(): ?DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(DateTimeInterface $date)
    {
        $this->date = $date;
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
            $landmark->setPoint($this);
        }
    }

    public function removeLandmark(Landmark $landmark)
    {
        if ($this->landmarks->removeElement($landmark)) {
            if ($landmark->getPoint() === $this) {
                $landmark->setPoint(null);
            }
        }
    }
}
