<?php

namespace App\Entity;

use App\Repository\FriendRequestRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: FriendRequestRepository::class)]
class FriendRequest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['request:read'])]
    private ?Profile $sentBy = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['request:read'])]
    private ?Profile $receivedBy = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSentBy(): ?Profile
    {
        return $this->sentBy;
    }

    public function setSentBy(?Profile $sentBy): static
    {
        $this->sentBy = $sentBy;

        return $this;
    }

    public function getReceivedBy(): ?Profile
    {
        return $this->receivedBy;
    }

    public function setReceivedBy(?Profile $receivedBy): static
    {
        $this->receivedBy = $receivedBy;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }
}
