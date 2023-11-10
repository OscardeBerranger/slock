<?php

namespace App\Entity;

use App\Repository\PrivateMessageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PrivateMessageRepository::class)]
class PrivateMessage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('conv:read')]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups("message:read")]
    private ?PrivateConversation $privateConversation = null;

    #[ORM\ManyToOne(inversedBy: 'privateMessages')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups("message:read")]
    private ?Profile $author = null;

    #[ORM\Column(length: 255)]
    #[Groups("message:read")]
    private ?string $content = null;

    #[ORM\Column]
    #[Groups("message:read")]
    private ?\DateTimeImmutable $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrivateConversation(): ?PrivateConversation
    {
        return $this->privateConversation;
    }

    public function setPrivateConversation(?PrivateConversation $privateConversation): static
    {
        $this->privateConversation = $privateConversation;

        return $this;
    }

    public function getAuthor(): ?Profile
    {
        return $this->author;
    }

    public function setAuthor(?Profile $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

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
}
