<?php

namespace App\Entity;

use App\Entity\Track;
use App\Repository\LocationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;

#[Entity(repositoryClass: LocationRepository::class)]
class Location
{
    #[Id]
    #[GeneratedValue]
    #[Column]
    private ?int $id = null;

    #[Column(length: 100)]
    private ?string $name = null;

    #[Column]
    private ?int $countyId = null;

    #[ManyToOne(inversedBy: "locations")]
    #[JoinColumn(nullable: false)]
    private ?County $county = null;

    #[OneToMany(mappedBy: "startLocation", targetEntity: Track::class)]
    private Collection $tracks;

    #[OneToMany(mappedBy: 'location', targetEntity: TrackLocation::class)]
    private Collection $trackLocations;

    public function __construct()
    {
        $this->tracks = new ArrayCollection();
        $this->trackLocations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getCountyId(): ?int
    {
        return $this->countyId;
    }

    public function setCountyId(int $countyId)
    {
        $this->countyId = $countyId;
    }

    public function getCounty(): ?County
    {
        return $this->county;
    }

    public function setCounty(?County $county)
    {
        $this->county = $county;
    }

    /**
     * @return Collection<int, Track>
     */
    public function getTracks(): Collection
    {
        return $this->tracks;
    }

    public function addTrack(Track $track)
    {
        if (!$this->tracks->contains($track)) {
            $this->tracks->add($track);
            $track->setStartLocation($this);
        }
    }

    public function removeTrack(Track $track)
    {
        if ($this->tracks->removeElement($track)) {
            if ($track->getStartLocation() === $this) {
                $track->setStartLocation(null);
            }
        }
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
            $trackLocation->setLocation($this);
        }
    }

    public function removeTrackLocation(TrackLocation $trackLocation)
    {
        if ($this->trackLocations->removeElement($trackLocation)) {
            if ($trackLocation->getLocation() === $this) {
                $trackLocation->setLocation(null);
            }
        }
    }
}
