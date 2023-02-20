<?php

namespace App\Entity;

use App\Repository\AnnoncesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Categorie;
use PhpParser\Node\Expr\Cast\String_;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AnnoncesRepository::class)]
class Annonces
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: "veuillez remplir ce champ")]
    private ?string $Description = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Assert\NotBlank(message: "veuillez entrer une image")]
    private $Image = null;

    #[ORM\ManyToOne(inversedBy: 'Annonces')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categorie $categorie = null;

    #[ORM\Column(type: Types::STRING)]
    private $DatePublication = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "veuillez remplir ce champ")]
    private ?string $adresse = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "veuillez remplir ce champ")]
    private ?int $Quantite = null;

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

    public function getQuantite(): ?int
    {
        return $this->Quantite;
    }

    public function setQuantite(int $Quantite): self
    {
        $this->Quantite = $Quantite;

        return $this;
    }
}
