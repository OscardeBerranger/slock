<?php

namespace App\Entity;

use App\Repository\RelationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RelationRepository::class)]
class Relation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('relation:read')]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'asSender')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('relation:read')]

    private ?Profile $sender = null;

    #[ORM\ManyToOne(inversedBy: 'asRecipient')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('relation:read')]

    private ?Profile $recipient = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSender(): ?Profile
    {
        return $this->sender;
    }

    public function setSender(?Profile $sender): static
    {
        $this->sender = $sender;

        return $this;
    }

    public function getRecipient(): ?Profile
    {
        return $this->recipient;
    }

    public function setRecipient(?Profile $recipient): static
    {
        $this->recipient = $recipient;

        return $this;
    }
}
