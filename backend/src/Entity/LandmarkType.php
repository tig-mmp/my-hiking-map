<?php

namespace App\Entity;

use App\Entity\Landmark;
use App\Repository\LandmarkTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;

#[Entity(repositoryClass: LandmarkTypeRepository::class)]
class LandmarkType
{
    #[Id]
    #[GeneratedValue]
    #[Column]
    private ?int $id = null;

    #[Column(length: 100)]
    private ?string $name = null;

    #[OneToMany(mappedBy: "landmarkType", targetEntity: Landmark::class)]
    private Collection $landmarks;

    public function serializeShort(): array
    {
        return ["id" => $this->id, "name" => $this->name];
    }

    public function serializeForm(): array
    {
        return ["id" => $this->id, "name" => $this->name];
    }

    public function serializeList(): array
    {
        return ["id" => $this->id, "name" => $this->name];
    }

    public function __construct()
    {
        $this->landmarks = new ArrayCollection();
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
            $landmark->setLandmarkType($this);
        }
    }

    public function removeLandmark(Landmark $landmark)
    {
        if ($this->landmarks->removeElement($landmark)) {
            if ($landmark->getLandmarkType() === $this) {
                $landmark->setLandmarkType(null);
            }
        }
    }
}
