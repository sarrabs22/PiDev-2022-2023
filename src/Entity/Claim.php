<?php

namespace App\Entity;

use App\Repository\ClaimRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClaimRepository::class)]
class Claim
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Don $donation = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?User $donner = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?User $receiver = null;

    #[ORM\Column]
    private ?int $total_quantity = null;

    #[ORM\Column]
    private ?int $received_quantity = null;

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDonationId(): ?Don
    {
        return $this->donation;
    }

    public function setDonationId(?Don $donation): self
    {
        $this->donation = $donation;

        return $this;
    }

    public function getDonnerId(): ?User
    {
        return $this->donner;
    }

    public function setDonnerId(?User $donner): self
    {
        $this->donner = $donner;

        return $this;
    }

    public function getReceiverId(): ?User
    {
        return $this->receiver;
    }

    public function setReceiverId(?User $receiver): self
    {
        $this->receiver = $receiver;

        return $this;
    }

    public function getTotalQuantity(): ?int
    {
        return $this->total_quantity;
    }

    public function setTotalQuantity(int $total_quantity): self
    {
        $this->total_quantity = $total_quantity;

        return $this;
    }

    public function getReceivedQuantity(): ?int
    {
        return $this->received_quantity;
    }

    public function setReceivedQuantity(int $received_quantity): self
    {
        $this->received_quantity = $received_quantity;

        return $this;
    }

    
}
