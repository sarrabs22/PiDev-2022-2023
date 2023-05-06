<?php

namespace App\Entity;

use App\Repository\AnnoncesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Categorie;
use PhpParser\Node\Expr\Cast\String_;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\HttpClient\HttpClient;

/* $client = HttpClient::create();
$response = $client->request('GET', 'templates\annonce_crud\affichage1.html.twig');
$content = $response->getContent(); */

#[ORM\Entity(repositoryClass: AnnoncesRepository::class)]
class Annonces
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("annonce/crud")]
    private ?int $id = null;



    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: "veuillez remplir ce champ")]
    #[Groups("annonce/crud")]
    private ?string $Description = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Assert\NotBlank(message: "veuillez entrer une image")]
    #[Groups("annonce/crud")]
    private $Image = null;

    #[ORM\ManyToOne(inversedBy: 'Annonces')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups("annonce/crud")]
    private ?Categorie $categorie = null;

    #[ORM\Column(type: Types::STRING)]
    #[Groups("annonce/crud")]
    private $DatePublication = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "veuillez remplir ce champ")]
    #[Groups("annonce/crud")]
    private ?string $adresse = null;

    #[ORM\OneToMany(mappedBy: 'annonces', targetEntity: Commentaires::class, orphanRemoval: true)]
    private Collection $commentaires;

    #[ORM\Column(nullable: true)]
    private ?int $nombreEtoiles = 0;

    #[ORM\ManyToOne(inversedBy: 'annonces')]
    private ?User $user = null;

    #[ORM\Column(nullable: true)]
    private ?int $rated = null;

    
    public function __construct()
    {
        $this->commentaires = new ArrayCollection();
    }

    /*  #[ORM\Column]
    #[Assert\NotBlank(message: "veuillez remplir ce champ")]
    #[Assert\GreaterThanOrEqual(0, message: "veuillez saisir un nombre positif")]

    private ?int $Quantite = null; */



    public function getId(): ?int
    {
        return $this->id;
    }



    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    public function getImage()
    {
        return $this->Image;
    }

    public function setImage($Image): self
    {
        $this->Image = $Image;

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

    public function getDatePublication(): ?string
    {
        return $this->DatePublication;
    }

    public function setDatePublication(String $DatePublication): self
    {
        $this->DatePublication = $DatePublication;

        return $this;
    }

    public function getadresse(): ?string
    {
        return $this->adresse;
    }

    public function setadresse(string $adresse): self
    {
        $this->adresse = $adresse;

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
            $commentaire->setAnnonces($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaires $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getAnnonces() === $this) {
                $commentaire->setAnnonces(null);
            }
        }

        return $this;
    }

    public function getNombreEtoiles(): ?int
    {
        return $this->nombreEtoiles;
    }

    public function setNombreEtoiles(?int $nombreEtoiles): self
    {
        $this->nombreEtoiles = $nombreEtoiles;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getRated(): ?int
    {
        return $this->rated;
    }

    public function setRated(?int $rated): self
    {
        $this->rated = $rated;

        return $this;
    }

    
}
