<?php

namespace App\Entity;

use App\Repository\LandmarkTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;

#[Entity(repositoryClass: LandmarkTypeRepository::class)]
class LandmarkType
{
    #[Id]
    #[GeneratedValue]
    #[Column]
    private ?int $id = null;

    #[Column(length: 100)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'landmarkType', targetEntity: Landmark::class)]
    private Collection $landmarks;

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

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Landmark>
     */
    public function getLandmarks(): Collection
    {
        return $this->landmarks;
    }

    public function addLandmark(Landmark $landmark): static
    {
        if (!$this->landmarks->contains($landmark)) {
            $this->landmarks->add($landmark);
            $landmark->setLandmarkType($this);
        }

        return $this;
    }

    public function removeLandmark(Landmark $landmark): static
    {
        if ($this->landmarks->removeElement($landmark)) {
            // set the owning side to null (unless already changed)
            if ($landmark->getLandmarkType() === $this) {
                $landmark->setLandmarkType(null);
            }
        }

        return $this;
    }
}
