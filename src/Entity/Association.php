<?php

namespace App\Entity;

use App\Repository\AssociationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Mime\Message;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AssociationRepository::class)]
class Association
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message:" le champ est vide")]
    private ?string $nom = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:" le champ est vide")]
    #[Assert\Length(max:8,min:8,maxMessage:"le numero  doit etre composé de 8 chiffres")]
    private ?int $numero = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"le champ est vide")]
    #[Assert\Email(message:"the email '{{ value }}'is not a valid email")]
    private ?string $mail = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"le champ est vide")]
    private ?string $adresse = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"le champ est vide")]
    #[Assert\Length(max:4,min:4,maxMessage:"le numero du code postal doit etre composé de 4 chiffres")]
    private ?int $CodePostal = null;

    #[ORM\Column(length: 80)]
    #[Assert\NotBlank(message:"le champ est vide")]
    private ?string $ville = null;

    #[ORM\ManyToOne(inversedBy: 'associations')]
    private ?Categorie $categorie = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:" le champ est vide")]
    private ?string $Image = null;

        
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

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): self
    {
        $this->numero = $numero;

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

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCodePostal(): ?int
    {
        return $this->CodePostal;
    }

    public function setCodePostal(int $CodePostal): self
    {
        $this->CodePostal = $CodePostal;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

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

    public function getImage(): ?string
    {
        return $this->Image;
    }

    public function setImage(string $Image): self
    {
        $this->Image = $Image;

        return $this;
    }
}
