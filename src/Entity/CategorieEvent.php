<?php

namespace App\Entity;

use App\Repository\CategorieEventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;


#[ORM\Entity(repositoryClass: CategorieEventRepository::class)]
class CategorieEvent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
   

    private ?int $id = null;

   
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Il faut insérer une categorie") ]
   
    public ?string $nom_categ_event = null;
    
   
   
    #[ORM\OneToMany(mappedBy: 'categorie', targetEntity: Evenement::class)]
    private Collection $events;

    public function __construct()
    {
        $this->events = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCategEvent(): ?string
    {
        return $this->nom_categ_event;
    }

    public function setNomCategEvent(string $nom_categ_event): self
    {
        $this->nom_categ_event = $nom_categ_event;

        return $this;
    }

    /**
     * @return Collection<int, Evenement>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Evenement $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
            $event->setCategorie($this);
        }

        return $this;
    }

    public function removeEvent(Evenement $event): self
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getCategorie() === $this) {
                $event->setCategorie(null);
            }
        }

        return $this;
    }
}
