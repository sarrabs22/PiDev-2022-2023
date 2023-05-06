<?php

namespace App\Entity;

use App\Repository\CategorieAssociationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieAssociationRepository::class)]
class CategorieAssociation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\OneToMany(mappedBy: 'categorieassociation', targetEntity: Association::class)]
    private Collection $associations;

    public function __construct()
    {
        $this->associations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection<int, association>
     */
    public function getAssociations(): Collection
    {
        return $this->associations;
    }

    public function addAssociation(association $association): self
    {
        if (!$this->associations->contains($association)) {
            $this->associations->add($association);
            $association->setCategorie($this);
        }

        return $this;
    }

    public function removeAssociation(association $association): self
    {
        if ($this->associations->removeElement($association)) {
            // set the owning side to null (unless already changed)
            if ($association->getCategorie() === $this) {
                $association->setCategorie(null);
            }
        }

        return $this;
    }
}
