<?php

namespace App\Entity;
use App\Entity\User;
use App\Repository\EvenementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
#[ORM\Entity(repositoryClass: EvenementRepository::class)]
class Evenement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("event")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Il faut insérer le nom") ]
    #[Groups("event")]
    public ?string $Nom_event = null;

    #[ORM\Column(type: Types::DATE_MUTABLE) ]
    
    #[Assert\GreaterThanOrEqual("today", message: "Veuillez saisir une date supérieure à la date d'aujourd'hui ")]
    
    private ?\DateTimeInterface $date_debut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
   
    #[Assert\GreaterThanOrEqual(propertyPath:"date_debut", message: "Veuillez saisir une date supérieure à la date debut ")]
   
    private ?\DateTimeInterface $date_fin = null;

    #[ORM\Column(length: 255)]
    
    #[Assert\NotBlank(message:"Il faut insérer l'adresse") ]
    private ?string $localisation = null;

    

    #[ORM\ManyToOne(inversedBy: 'events')]
    private ?Categorie $categorie = null;

    #[ORM\Column(length: 255)]
    
    #[Assert\NotBlank(message:"Il faut insérer une image") ] 
    public ?string $image_event = null;

    #[ORM\Column]
    
    #[Assert\GreaterThanOrEqual(0)]
    private ?int $nbParticipants =1;

    

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'events')]
    private Collection $user ;

    private $cancelled;

    public function __construct()
    {
        $this->cancelled = false; 
       
    }
    public function isCancelled()
    {
        return $this->cancelled;
    }

    public function cancel()
    {
        $this->cancelled = true;
    }

    public function isUserParticipating(User $user): bool
    {
        return $this->user->contains($user);
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

    

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
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

    

    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user->add($user);
            $user->addEvent($this);
        }

        return $this;
    }

    public function RemoveUser(User $user): self
    {
        $this->user->removeElement($user);

        return $this;
    }


}
