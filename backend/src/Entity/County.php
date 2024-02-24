<?php

namespace App\Entity;

use App\Entity\Location;
use App\Repository\CountyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;

#[Entity(repositoryClass: CountyRepository::class)]
class County
{
    #[Id]
    #[GeneratedValue]
    #[Column]
    private ?int $id = null;

    #[Column(length: 100)]
    private ?string $name = null;

    #[Column(length: 3, nullable: true)]
    private ?string $abbreviation = null;

    #[Column]
    private ?int $districtId = null;

    #[ManyToOne(inversedBy: "counties")]
    #[JoinColumn(nullable: false)]
    private ?District $district = null;

    #[OneToMany(mappedBy: "county", targetEntity: Location::class, orphanRemoval: true)]
    private Collection $locations;

    public function __construct(string $name, District $district)
    {
        $this->locations = new ArrayCollection();
        $this->name = $name;
        $this->setDistrict($district);
    }

    public function serializeList(): array
    {
        return ["id" => $this->id, "name" => $this->name, "districtId" => $this->districtId];
    }

    public function serializeForm(): array
    {
        return ["name" => $this->name, "districtId" => $this->districtId];
    }

    public function serializeShort(): array
    {
        return ["id" => $this->id, "name" => $this->name, "locations" => $this->getLocationsShort()];
    }

    private function getLocationsShort(): array
    {
        $locations = [];
        foreach ($this->locations as $location) {
            $locations[] = $location->serializeShort();
        }
        return $locations;
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

    public function getAbbreviation(): ?string
    {
        return $this->abbreviation;
    }

    public function setAbbreviation(?string $abbreviation)
    {
        $this->abbreviation = $abbreviation;
    }

    public function getDistrictId(): ?int
    {
        return $this->districtId;
    }

    public function setDistrictId(int $districtId)
    {
        $this->districtId = $districtId;
    }

    public function getDistrict(): ?District
    {
        return $this->district;
    }

    public function setDistrict(?District $district)
    {
        $this->district = $district;
        $this->districtId = $district ? $district->getId() : null;
    }

    /**
     * @return Collection<int, Location>
     */
    public function getLocations(): Collection
    {
        return $this->locations;
    }

    public function addLocation(Location $location)
    {
        if (!$this->locations->contains($location)) {
            $this->locations->add($location);
            $location->setCounty($this);
        }
    }

    public function removeLocation(Location $location)
    {
        if ($this->locations->removeElement($location)) {
            if ($location->getCounty() === $this) {
                $location->setCounty(null);
            }
        }
    }
}
