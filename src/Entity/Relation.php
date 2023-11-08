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

    #[ORM\ManyToOne(inversedBy: 'relationAsSender')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('relation:read')]

    private ?Profile $relationAsSender = null;

    #[ORM\ManyToOne(inversedBy: 'relationAsRecipient')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('relation:read')]

    private ?Profile $relationAsRecipient = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRelationAsSender(): ?Profile
    {
        return $this->relationAsSender;
    }

    public function setRelationAsSender(?Profile $relationAsSender): static
    {
        $this->relationAsSender = $relationAsSender;

        return $this;
    }

    public function getRelationAsRecipient(): ?Profile
    {
        return $this->relationAsRecipient;
    }

    public function setRelationAsRecipient(?Profile $relationAsRecipient): static
    {
        $this->relationAsRecipient = $relationAsRecipient;

        return $this;
    }
}
