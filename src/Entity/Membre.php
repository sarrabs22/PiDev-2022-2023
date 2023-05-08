<?php

namespace App\Entity;

use App\Repository\MembreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MembreRepository::class)]
class Membre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(length: 100)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $mail = null;

    #[ORM\Column]
    private ?int $age = null;

    #[ORM\Column(length: 500)]
    private ?string $passions = null;



    #[ORM\Column(length: 10000)]
    private ?string $experience = null;

    #[ORM\Column(length: 1000)]
    private ?string $ClubEntendu = null;

    #[ORM\Column(length: 255)]
    private ?string $actions = null;



    protected $captchaCode;

   

    #[ORM\ManyToMany(targetEntity: Association::class, mappedBy: 'Membres')]
    private Collection $associations;

    #[ORM\ManyToOne(inversedBy: 'membres')]
    private ?User $User = null;

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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getPassions(): ?string
    {
        return $this->passions;
    }

    public function setPassions(string $passions): self
    {
        $this->passions = $passions;

        return $this;
    }

   
  

    public function getExperience(): ?string
    {
        return $this->experience;
    }

    public function setExperience(string $experience): self
    {
        $this->experience = $experience;

        return $this;
    }

    public function getClubEntendu(): ?string
    {
        return $this->ClubEntendu;
    }

    public function setClubEntendu(string $ClubEntendu): self
    {
        $this->ClubEntendu = $ClubEntendu;

        return $this;
    }

    public function getActions(): ?string
    {
        return $this->actions;
    }

    public function setActions(string $actions): self
    {
        $this->actions = $actions;

        return $this;
    }

    
    /**
     * @return Collection<int, Association>
     */
    public function getAssociations(): Collection
    {
        return $this->associations;
    }

    public function addAssociation(Association $association): self
    {
        if (!$this->associations->contains($association)) {
            $this->associations->add($association);
            $association->addMembre($this);
        }

        return $this;
    }

    public function removeAssociation(Association $association): self
    {
        if ($this->associations->removeElement($association)) {
            $association->removeMembre($this);
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $user): self
    {
        $this->User = $user;

        return $this;
    }

    
}
