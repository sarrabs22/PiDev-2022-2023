<?php

namespace App\Entity;

use App\Repository\CategorieRecRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieRecRepository::class)]
class CategorieRec
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"nom is required")]
    #[Assert\Length(
        min : 8,
        max : 20,
        minMessage : "Nom doit être au moins {{ limit }} characters long",
        maxMessage : "Nom ne peut pas etre plus {{ limit }} characters"
    )]   
    private ?string $Nom = null;

    #[ORM\OneToMany(mappedBy: 'categorieRec', targetEntity: Reclamation::class)]
    private Collection $Reclamations;

    public function __construct()
    {
        $this->Reclamations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    /**
     * @return Collection<int, Reclamation>
     */
    public function getReclamations(): Collection
    {
        return $this->Reclamations;
    }

    public function addReclamation(Reclamation $reclamation): self
    {
        if (!$this->Reclamations->contains($reclamation)) {
            $this->Reclamations->add($reclamation);
            $reclamation->setCategorieRec($this);
        }

        return $this;
    }

    public function removeReclamation(Reclamation $reclamation): self
    {
        if ($this->Reclamations->removeElement($reclamation)) {
            // set the owning side to null (unless already changed)
            if ($reclamation->getCategorieRec() === $this) {
                $reclamation->setCategorieRec(null);
            }
        }

        return $this;
    }
}
