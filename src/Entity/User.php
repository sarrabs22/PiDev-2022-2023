<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert ;
use Symfony\Component\Serializer\Annotation\Groups;
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'il existe un compte enregistré avec cette adresse')]
#[UniqueEntity(fields: ['NumTelephone'], message: 'le numero de telehpone existe deja')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]

class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("user")]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\Email(
        message: 'Cette email {{ value }} n"est pas valide',
    )]
    #[Groups("user")]
    private ?string $email = null;

    #[ORM\Column]
    #[Groups("user")]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Groups("user")]
    private ?string $password = null;

    #[ORM\Column(type: 'boolean')]
    #[Groups("user")]
    private $isVerified = false;

    #[ORM\Column(length: 255)]
    #[Assert\Regex(
        pattern : "/^\d{8}$/",
        message :"le numéro doit avoir 8 chiffre"
    )]
    #[Assert\NotBlank(
        message : "veuillez inserer votre numtelephone"
    )]
    #[Groups("user")]
    private ?string $NumTelephone = null;

    #[ORM\Column(length: 255)]
    #[Groups("user")]
    private ?string $type = null;

    #[ORM\Column(nullable: true)]
    #[Groups("user")]
    private ?int $score = null;

    #[ORM\Column(nullable: true)]
    #[Groups("user")]
    private ?int $nb_etoile = null;

    #[ORM\ManyToMany(targetEntity: Exploit::class, inversedBy: 'users')]
    #[Groups("user")]
    private Collection $exploits;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(
        message : "veuillez inserer votre nom"
    )]
    #[Groups("user")]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(
        message : "veuillez inserer votre prenom"
    )]
    #[Groups("user")]
    private ?string $prenom = null;

    #[ORM\Column(length: 255, nullable: true)]
     #[Assert\NotBlank(
       
        message :"Vous devez selectionnez une photo de profil",
    )] 
    #[Groups("user")]
    private ?string $image = null;

    #[ORM\OneToMany(targetEntity:'Participant',mappedBy:'user')]
    private $participants ;

    #[ORM\OneToMany(targetEntity:'Message',mappedBy:'user')]
    private $messages ;
    public function __construct()
    {
        
        $this->exploits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return  $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): int
    {
        return (int) $this->id;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getNumTelephone(): ?string
    {
        return $this->NumTelephone;
    }

    public function setNumTelephone(string $NumTelephone): self
    {
        $this->NumTelephone = $NumTelephone;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(?int $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getNbEtoile(): ?int
    {
        return $this->nb_etoile;
    }

    public function setNbEtoile(int $nb_etoile): self
    {
        $this->nb_etoile = $nb_etoile;

        return $this;
    }

    /**
     * @return Collection<int, Exploit>
     */
    public function getExploits(): Collection
    {
        return $this->exploits;
    }

    public function addExploit(Exploit $exploit): self
    {
        if (!$this->exploits->contains($exploit)) {
            $this->exploits->add($exploit);
        }

        return $this;
    }

    public function removeExploit(Exploit $exploit): self
    {
        $this->exploits->removeElement($exploit);

        return $this;
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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    
}
