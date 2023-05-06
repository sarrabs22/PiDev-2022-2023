<?php

namespace App\Entity;

use App\Repository\ReclamationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Mime\Message;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ReclamationRepository::class)]
class Reclamation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("reclamation")]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING)]
    #[Groups("reclamation")]
    #[Assert\NotBlank(message:"date is required")]
    private ?string $data_reclamation = null;

    #[ORM\Column(length: 255)]
    #[Groups("reclamation")]
    private ?string $etat = null;

    #[ORM\ManyToOne(inversedBy: 'Reclamations')]
    private ?CategorieRec $categorieRec = null;

    #[ORM\Column(length: 255)]
    #[Groups("reclamation")]
    #[Assert\NotBlank(message:"motif is required")]
    private ?string $MotifDeReclamation = null;

    #[ORM\Column(length: 255)]
    #[Groups("reclamation")]
    #[Assert\NotBlank(message:"image is required")]
    private ?string $Image = null;

    #[ORM\Column]
    #[Groups("reclamation")]
    private ?int $NumTelephone = null;

    #[ORM\Column(length: 255)]
    #[Groups("reclamation")]
    private ?string $Email = null;

    #[ORM\ManyToOne(inversedBy: 'Reclamations')]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'reclamations', targetEntity: Comments::class, orphanRemoval: true)]
    private Collection $comments;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
            $comment->setReclamations($this);
        }

        return $this;
    }

    public function removeComment(Comments $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getReclamations() === $this) {
                $comment->setReclamations(null);
            }
        }

        return $this;
    }
}
