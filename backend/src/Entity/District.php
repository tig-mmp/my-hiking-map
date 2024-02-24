<?php

namespace App\Entity;

use App\Entity\County;
use App\Repository\DistrictRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;

#[Entity(repositoryClass: DistrictRepository::class)]
class District
{
    #[Id]
    #[GeneratedValue]
    #[Column]
    private ?int $id = null;

    #[Column(length: 100)]
    private ?string $name = null;

    #[OneToMany(mappedBy: "district", targetEntity: County::class, orphanRemoval: true)]
    private Collection $counties;

    public function __construct(string $name)
    {
        $this->counties = new ArrayCollection();
        $this->name = $name;
    }

    public function serializeList(): array
    {
        return ["id" => $this->id, "name" => $this->name];
    }

    public function serializeForm(): array
    {
        return ["name" => $this->name];
    }

    public function serializeShort(): array
    {
        return ["id" => $this->id, "name" => $this->name, "counties" => $this->getCountiesShort()];
    }

    private function getCountiesShort(): array
    {
        $counties = [];
        foreach ($this->counties as $county) {
            $counties[] = $county->serializeShort();
        }
        return $counties;
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

    /**
     * @return Collection<int, County>
     */
    public function getCounties(): Collection
    {
        return $this->counties;
    }

    public function addCounty(County $county)
    {
        if (!$this->counties->contains($county)) {
            $this->counties->add($county);
            $county->setDistrict($this);
        }
    }

    public function removeCounty(County $county)
    {
        if ($this->counties->removeElement($county)) {
            if ($county->getDistrict() === $this) {
                $county->setDistrict(null);
            }
        }
    }
}
