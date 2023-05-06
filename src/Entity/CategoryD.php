<?php

namespace App\Entity;

use App\Repository\CategoryDRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryDRepository::class)]
class CategoryD
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $NameCa = null;

    #[ORM\OneToMany(mappedBy: 'categoryD', targetEntity: Don::class)]
    private Collection $Dons;

    public function __construct()
    {
        $this->Dons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameCa(): ?string
    {
        return $this->NameCa;
    }

    public function setNameCa(string $NameCa): self
    {
        $this->NameCa = $NameCa;

        return $this;
    }

    /**
     * @return Collection<int, Don>
     */
    public function getDons(): Collection
    {
        return $this->Dons;
    }

    public function addDon(Don $don): self
    {
        if (!$this->Dons->contains($don)) {
            $this->Dons->add($don);
            $don->setCategoryD($this);
        }

        return $this;
    }

    public function removeDon(Don $don): self
    {
        if ($this->Dons->removeElement($don)) {
            // set the owning side to null (unless already changed)
            if ($don->getCategoryD() === $this) {
                $don->setCategoryD(null);
            }
        }

        return $this;
    }
}
