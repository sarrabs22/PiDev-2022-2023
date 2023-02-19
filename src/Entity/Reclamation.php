<?php

namespace App\Entity;

use App\Repository\ReclamationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Mime\Message;

#[ORM\Entity(repositoryClass: ReclamationRepository::class)]
class Reclamation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message:"contenu is required")]
    private ?string $contenu = null;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank(message:"date is required")]
    private ?string $data_reclamation = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"etat is required")]
    private ?string $etat = null;

    #[ORM\ManyToOne(inversedBy: 'Reclamations')]
    private ?CategorieRec $categorieRec = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"motif is required")]
    private ?string $MotifDeReclamation = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"image is required")]
    private ?string $Image = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"numtel is required")]
    #[Assert\Length(
        min: 8,
        max: 8,
        minMessage: 'Your Number must be at least {{ limit }} numbers long',
        maxMessage: 'Your  Number cannot be longer than {{ limit }} numbers',
    )]
    private ?int $NumTelephone = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: " email is required")]
    #[Assert\Email(message:"the email {{ value }} is not a valid email")]
    private ?string $Email = null;

    #[ORM\ManyToOne(inversedBy: 'Reclamations')]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getDataReclamation(): ?string
    {
        return $this->data_reclamation;
    }

    public function setDataReclamation(string $data_reclamation): self
    {
        $this->data_reclamation = $data_reclamation;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getCategorieRec(): ?CategorieRec
    {
        return $this->categorieRec;
    }

    public function setCategorieRec(?CategorieRec $categorieRec): self
    {
        $this->categorieRec = $categorieRec;

        return $this;
    }

    public function getMotifDeReclamation(): ?string
    {
        return $this->MotifDeReclamation;
    }

    public function setMotifDeReclamation(string $MotifDeReclamation): self
    {
        $this->MotifDeReclamation = $MotifDeReclamation;

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

    public function getNumTelephone(): ?int
    {
        return $this->NumTelephone;
    }

    public function setNumTelephone(int $NumTelephone): self
    {
        $this->NumTelephone = $NumTelephone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->Email;
    }

    public function setEmail(string $Email): self
    {
        $this->Email = $Email;

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
