<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

use App\Repository\DonRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: DonRepository::class)]
class Don
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[Groups("dons")]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups("dons")]
    #[Assert\NotBlank(message: "name is required")]
    private ?string $NameD = null;

    #[ORM\Column]
    #[Groups("dons")]
    #[Assert\NotBlank(message: "Quantity is required")]
    private ?int $quantite = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups("dons")]
    #[Assert\NotBlank(message: "Description is required")]
    private ?string $Description = null;

    #[ORM\Column(length: 255)]
    #[Groups("dons")]
    #[Assert\NotBlank(message: "Localisation is required")]
    private ?string $Localisation = null;

    #[ORM\ManyToOne(inversedBy: 'Dons')]
    #[Groups("dons")]
    private ?CategoryD $categoryD = null;

    #[ORM\Column(length: 255)]
    #[Groups("dons")]
    #[Assert\NotBlank(message: "Please fill this Image")]
    private ?string $Image = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Email is required")]
    #[Groups("dons")]
    #[Assert\Email(message: "the email '{{ value }}'is not a valid email")]
    private ?string $email = null;


    #[ORM\Column]
    #[Assert\NotBlank(message: "Number is required")]
    #[Groups("dons")]
    #[Assert\Length(
        min: 8,
        max: 8,
        minMessage: 'Your Number must be at least {{ limit }} numbers long',
        maxMessage: 'Your  Number cannot be longer than {{ limit }} numbers',
    )]
    private ?int $Numero = null;

    #[ORM\ManyToOne(inversedBy: 'dons')]
    private ?User $user = null;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameD(): ?string
    {
        return $this->NameD;
    }

    public function setNameD(string $NameD): self
    {
        $this->NameD = $NameD;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
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

    public function getLocalisation(): ?string
    {
        return $this->Localisation;
    }

    public function setLocalisation(string $Localisation): self
    {
        $this->Localisation = $Localisation;

        return $this;
    }

    public function getCategoryD(): ?CategoryD
    {
        return $this->categoryD;
    }

    public function setCategoryD(?CategoryD $categoryD): self
    {
        $this->categoryD = $categoryD;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getNumero(): ?int
    {
        return $this->Numero;
    }

    public function setNumero(int $Numero): self
    {
        $this->Numero = $Numero;

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
}
