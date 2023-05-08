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

    #[ORM\Column(nullable: true)]
    #[Groups("user")]
    private ?int $blocked = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Annonces::class)]
    private Collection $annonces;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Commentaires::class)]
    private Collection $commentaires;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Comments::class)]
    private Collection $comments;

    #[ORM\ManyToMany(targetEntity: Evenement::class, inversedBy: 'users')]
    private Collection $evenement;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Association::class)]
    private Collection $associations;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Don::class)]
    private Collection $dons;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Membre::class)]
    private Collection $membres;

  

   
    public function __construct()
    {
        
        $this->exploits = new ArrayCollection();
        $this->annonces = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->evenement = new ArrayCollection();
        $this->associations = new ArrayCollection();
        $this->dons = new ArrayCollection();
        $this->membres = new ArrayCollection();
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

    public function getBlocked(): ?int
    {
        return $this->blocked;
    }

    public function setBlocked(?int $blocked): self
    {
        $this->blocked = $blocked;

        return $this;
    }

    /**
     * @return Collection<int, Annonces>
     */
    public function getAnnonces(): Collection
    {
        return $this->annonces;
    }

    public function addAnnonce(Annonces $annonce): self
    {
        if (!$this->annonces->contains($annonce)) {
            $this->annonces->add($annonce);
            $annonce->setUser($this);
        }

        return $this;
    }

    public function removeAnnonce(Annonces $annonce): self
    {
        if ($this->annonces->removeElement($annonce)) {
            // set the owning side to null (unless already changed)
            if ($annonce->getUser() === $this) {
                $annonce->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Commentaires>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaires $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires->add($commentaire);
            $commentaire->setUser($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaires $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getUser() === $this) {
                $commentaire->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comments>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comments $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comments $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Evenement>
     */
    public function getEvenement(): Collection
    {
        return $this->evenement;
    }

    public function addEvenement(Evenement $evenement): self
    {
        if (!$this->evenement->contains($evenement)) {
            $this->evenement->add($evenement);
        }

        return $this;
    }

    public function removeEvenement(Evenement $evenement): self
    {
        $this->evenement->removeElement($evenement);

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
            $association->setUser($this);
        }

        return $this;
    }

    public function removeAssociation(Association $association): self
    {
        if ($this->associations->removeElement($association)) {
            // set the owning side to null (unless already changed)
            if ($association->getUser() === $this) {
                $association->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Don>
     */
    public function getDons(): Collection
    {
        return $this->dons;
    }

    public function addDon(Don $don): self
    {
        if (!$this->dons->contains($don)) {
            $this->dons->add($don);
            $don->setUser($this);
        }

        return $this;
    }

    public function removeDon(Don $don): self
    {
        if ($this->dons->removeElement($don)) {
            // set the owning side to null (unless already changed)
            if ($don->getUser() === $this) {
                $don->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Membre>
     */
    public function getMembres(): Collection
    {
        return $this->membres;
    }

    public function addMembre(Membre $membre): self
    {
        if (!$this->membres->contains($membre)) {
            $this->membres->add($membre);
            $membre->setUser($this);
        }

        return $this;
    }

    public function removeMembre(Membre $membre): self
    {
        if ($this->membres->removeElement($membre)) {
            // set the owning side to null (unless already changed)
            if ($membre->getUser() === $this) {
                $membre->setUser(null);
            }
        }

        return $this;
    }

    

    
    
}
