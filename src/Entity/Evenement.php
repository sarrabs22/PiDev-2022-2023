<?php

namespace App\Entity;

use App\Repository\EvenementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EvenementRepository::class)]
class Evenement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]

    #[Groups("evenements")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Il faut insérer le nom") ]
    #[Groups("evenements")]
    public ?string $Nom_event = null;

    #[ORM\Column(type: Types::DATE_MUTABLE) ]
    
    #[Assert\GreaterThanOrEqual("today", message: "Veuillez saisir une date supérieure à la date d'aujourd'hui ")]
    #[Groups("evenements")]
    private ?\DateTimeInterface $date_debut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
   
    #[Assert\GreaterThanOrEqual(propertyPath:"date_debut", message: "Veuillez saisir une date supérieure à la date debut ")]
    #[Groups("evenements")]
    private ?\DateTimeInterface $date_fin = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Il faut insérer l'adresse") ]
    #[Groups("evenements")]
    private ?string $localisation = null;

    
    #[ORM\ManyToOne(inversedBy: 'events')]
    
    private ?CategorieEvent $categorie = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Il faut insérer une image") ] 
    #[Groups("evenements")]
    public ?string $image_event = null;

    #[ORM\Column]
    #[Assert\GreaterThanOrEqual(0)]
    #[Groups("evenements")]
    private ?int $nbParticipants =1;

    

    

    private $cancelled;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'evenement')]
    private Collection $users;

    public function __construct()
    {
        $this->cancelled = false;
        $this->users = new ArrayCollection(); 
       
    }
    public function isCancelled()
    {
        return $this->cancelled;
    }

    public function cancel()
    {
        $this->cancelled = true;
    }

   

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomEvent(): ?string
    {
        return $this->Nom_event;
    }

    public function setNomEvent(string $Nom_event): self
    {
        $this->Nom_event = $Nom_event;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->date_debut;
    }

    public function setDateDebut(\DateTimeInterface $date_debut): self
    {
        $this->date_debut = $date_debut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDateFin(\DateTimeInterface $date_fin): self
    {
        $this->date_fin = $date_fin;

        return $this;
    }

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(string $localisation): self
    {
        $this->localisation = $localisation;

        return $this;
    }

    

    public function getCategorie(): ?CategorieEvent
    {
        return $this->categorie;
    }

    public function setCategorie(?CategorieEvent $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getImageEvent(): ?string
    {
        return $this->image_event;
    }

    public function setImageEvent(string $image_event): self
    {
        $this->image_event = $image_event;

        return $this;
    }

    public function getNbParticipants(): ?int
    {
        return $this->nbParticipants;
    }

    public function setNbParticipants(int $nbParticipants): self
    {
        $this->nbParticipants = $nbParticipants;

        return $this;
    }

   
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addEvenement($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeEvenement($this);
        }

        return $this;
    }

    

  


}
