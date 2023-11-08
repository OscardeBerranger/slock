<?php

namespace App\Entity;

use App\Repository\ProfileRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProfileRepository::class)]
class Profile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['request:read', 'relation:read'])]

    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['user:read', 'request:read', 'relation:read'])]
    private ?string $username = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['user:read'])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['user:read'])]
    private ?string $lastname = null;

    #[ORM\OneToOne(inversedBy: 'profile', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $ofUser = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\OneToMany(mappedBy: 'relationAsSender', targetEntity: Relation::class, orphanRemoval: true)]
    private Collection $relationAsSender;

    #[ORM\OneToMany(mappedBy: 'relationAsRecipient', targetEntity: Relation::class, orphanRemoval: true)]
    private Collection $relationAsRecipient;

public function getFriendsList()
{
    $list = [];
    foreach ($this->relationAsSender as $relation){

        if($relation->getRelationAsSender() != $this)
        {
            $otherProfile = $relation->getRelationAsSender();

        }else{
            $otherProfile = $relation->getRelationAsRecipient();
        }
        $list[]=$otherProfile;
    }
    foreach ($this->relationAsRecipient as $relation){

        if($relation->getRelationAsSender() != $this)
        {
            $otherProfile = $relation->getRelationAsSender();

        }else{
            $otherProfile = $relation->getRelationAsRecipient();
        }
        $list[]=$otherProfile;
    }

return $list;
}

    public function __construct()
    {
        $this->relationAsSender = new ArrayCollection();
        $this->relationAsRecipient = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getOfUser(): ?User
    {
        return $this->ofUser;
    }

    public function setOfUser(User $ofUser): static
    {
        $this->ofUser = $ofUser;

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

    /**
     * @return Collection<int, Relation>
     */
    public function getFriends(): Collection
    {
        return $this->relationAsSender;
    }

    public function addFriend(Relation $friend): static
    {
        if (!$this->relationAsSender->contains($friend)) {
            $this->relationAsSender->add($friend);
            $friend->setRelationAsSender($this);
        }

        return $this;
    }

    public function removeFriend(Relation $friend): static
    {
        if ($this->relationAsSender->removeElement($friend)) {
            // set the owning side to null (unless already changed)
            if ($friend->getRelationAsSender() === $this) {
                $friend->setRelationAsSender(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Relation>
     */
    public function getRelationAsRecipient(): Collection
    {
        return $this->relationAsRecipient;
    }

    public function addRelationAsRecipient(Relation $relationAsRecipient): static
    {
        if (!$this->relationAsRecipient->contains($relationAsRecipient)) {
            $this->relationAsRecipient->add($relationAsRecipient);
            $relationAsRecipient->setRelationAsRecipient($this);
        }

        return $this;
    }

    public function removeRelationAsRecipient(Relation $relationAsRecipient): static
    {
        if ($this->relationAsRecipient->removeElement($relationAsRecipient)) {
            // set the owning side to null (unless already changed)
            if ($relationAsRecipient->getRelationAsRecipient() === $this) {
                $relationAsRecipient->setRelationAsRecipient(null);
            }
        }

        return $this;
    }



}
