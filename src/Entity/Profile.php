<?php

namespace App\Entity;

use App\Repository\ProfileRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

#[ORM\Entity(repositoryClass: ProfileRepository::class)]
class Profile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['request:read', 'relation:read', 'groupmessage:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['user:read', 'request:read', 'relation:read', 'conv:read', 'message:read', 'groupmessage:read'])]
    private ?string $username = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['user:read', 'conv:read', 'message:read', 'groupmessage:read'])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['user:read', 'conv:read', 'message:read', 'groupmessage:read'])]
    private ?string $lastname = null;

    #[ORM\OneToOne(inversedBy: 'profile', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $ofUser = null;

    #[ORM\Column]
    #[Groups('conv:read')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\OneToMany(mappedBy: 'sender', targetEntity: Relation::class, orphanRemoval: true)]
    private Collection $relationAsSender;

    #[ORM\OneToMany(mappedBy: 'recipient', targetEntity: Relation::class, orphanRemoval: true)]
    private Collection $relationAsRecipient;

    #[ORM\OneToMany(mappedBy: 'convCreator', targetEntity: PrivateConversation::class, orphanRemoval: true)]
    private Collection $createdPrivateConversations;

    #[ORM\OneToMany(mappedBy: 'convRecipient', targetEntity: PrivateConversation::class, orphanRemoval: true)]
    private Collection $receivedPrivateConversation;

    #[ORM\OneToMany(mappedBy: 'sender', targetEntity: PrivateMessage::class, orphanRemoval: true)]
    private Collection $privateMessages;

    #[ORM\ManyToMany(targetEntity: GroupConversation::class, mappedBy: 'convCreator')]
    private Collection $adminGroupConversation;

    #[ORM\ManyToMany(targetEntity: GroupConversation::class, mappedBy: 'convRecipient')]
    private Collection $recipientGroupConversation;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: GroupeMessage::class)]
    private Collection $groupeMessages;

public function getFriendsList()
{
    $list = [];
    foreach ($this->relationAsSender as $relation){

        if($relation->getSender() != $this)
        {
            $otherProfile = $relation->getSender();

        }else{
            $otherProfile = $relation->getRecipient();
        }
        $list[]=$otherProfile;
    }
    foreach ($this->relationAsRecipient as $relation){

        if($relation->getSender() != $this)
        {
            $otherProfile = $relation->getSender();

        }else{
            $otherProfile = $relation->getRecipient();
        }
        $list[]=$otherProfile;
    }

return $list;
}

public function isMyFriend($user){
    foreach ($this->relationAsSender as $relation){
        if ($relation->getRecipient()==$user){
            return true;
        }
    }
    foreach ($this->relationAsRecipient as $relation){
        if ($relation->getSender()==$user){
            return true;
        }
    }
    return false;
}
    public function __construct()
    {
        $this->relationAsSender = new ArrayCollection();
        $this->relationAsRecipient = new ArrayCollection();
        $this->createdPrivateConversations = new ArrayCollection();
        $this->receivedPrivateConversation = new ArrayCollection();
        $this->privateMessages = new ArrayCollection();
        $this->adminGroupConversation = new ArrayCollection();
        $this->recipientGroupConversation = new ArrayCollection();
        $this->groupeMessages = new ArrayCollection();
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
            $friend->setSender($this);
        }

        return $this;
    }

    public function removeFriend(Relation $friend): static
    {
        if ($this->relationAsSender->removeElement($friend)) {
            // set the owning side to null (unless already changed)
            if ($friend->getSender() === $this) {
                $friend->setSender(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Relation>
     */
    public function getRecipient(): Collection
    {
        return $this->relationAsRecipient;
    }

    public function addRelationAsRecipient(Relation $relationAsRecipient): static
    {
        if (!$this->relationAsRecipient->contains($relationAsRecipient)) {
            $this->relationAsRecipient->add($relationAsRecipient);
            $relationAsRecipient->setRecipient($this);
        }

        return $this;
    }

    public function removeRelationAsRecipient(Relation $relationAsRecipient): static
    {
        if ($this->relationAsRecipient->removeElement($relationAsRecipient)) {
            // set the owning side to null (unless already changed)
            if ($relationAsRecipient->getRecipient() === $this) {
                $relationAsRecipient->setRecipient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PrivateConversation>
     */
    public function getCreatedPrivateConversations(): Collection
    {
        return $this->createdPrivateConversations;
    }

    public function addCreatedPrivateConversation(PrivateConversation $createdPrivateConversation): static
    {
        if (!$this->createdPrivateConversations->contains($createdPrivateConversation)) {
            $this->createdPrivateConversations->add($createdPrivateConversation);
            $createdPrivateConversation->setConvCreator($this);
        }

        return $this;
    }

    public function removeCreatedPrivateConversation(PrivateConversation $createdPrivateConversation): static
    {
        if ($this->createdPrivateConversations->removeElement($createdPrivateConversation)) {
            // set the owning side to null (unless already changed)
            if ($createdPrivateConversation->getConvCreator() === $this) {
                $createdPrivateConversation->setConvCreator(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PrivateConversation>
     */
    public function getReceivedPrivateConversation(): Collection
    {
        return $this->receivedPrivateConversation;
    }

    public function addReceivedPrivateConversation(PrivateConversation $receivedPrivateConversation): static
    {
        if (!$this->receivedPrivateConversation->contains($receivedPrivateConversation)) {
            $this->receivedPrivateConversation->add($receivedPrivateConversation);
            $receivedPrivateConversation->setConvRecipient($this);
        }

        return $this;
    }

    public function removeReceivedPrivateConversation(PrivateConversation $receivedPrivateConversation): static
    {
        if ($this->receivedPrivateConversation->removeElement($receivedPrivateConversation)) {
            // set the owning side to null (unless already changed)
            if ($receivedPrivateConversation->getConvRecipient() === $this) {
                $receivedPrivateConversation->setConvRecipient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PrivateMessage>
     */
    public function getPrivateMessages(): Collection
    {
        return $this->privateMessages;
    }

    public function addPrivateMessage(PrivateMessage $privateMessage): static
    {
        if (!$this->privateMessages->contains($privateMessage)) {
            $this->privateMessages->add($privateMessage);
            $privateMessage->setSender($this);
        }

        return $this;
    }

    public function removePrivateMessage(PrivateMessage $privateMessage): static
    {
        if ($this->privateMessages->removeElement($privateMessage)) {
            // set the owning side to null (unless already changed)
            if ($privateMessage->getSender() === $this) {
                $privateMessage->setSender(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, GroupConversation>
     */
    public function getAdminGroupConversation(): Collection
    {
        return $this->adminGroupConversation;
    }

    public function addAdminGroupConversation(GroupConversation $adminGroupConversation): static
    {
        if (!$this->adminGroupConversation->contains($adminGroupConversation)) {
            $this->adminGroupConversation->add($adminGroupConversation);
            $adminGroupConversation->addConvCreator($this);
        }

        return $this;
    }

    public function removeAdminGroupConversation(GroupConversation $adminGroupConversation): static
    {
        if ($this->adminGroupConversation->removeElement($adminGroupConversation)) {
            $adminGroupConversation->removeConvCreator($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, GroupConversation>
     */
    public function getRecipientGroupConversation(): Collection
    {
        return $this->recipientGroupConversation;
    }

    public function addRecipientGroupConversation(GroupConversation $recipientGroupConversation): static
    {
        if (!$this->recipientGroupConversation->contains($recipientGroupConversation)) {
            $this->recipientGroupConversation->add($recipientGroupConversation);
            $recipientGroupConversation->addConvRecipient($this);
        }

        return $this;
    }

    public function removeRecipientGroupConversation(GroupConversation $recipientGroupConversation): static
    {
        if ($this->recipientGroupConversation->removeElement($recipientGroupConversation)) {
            $recipientGroupConversation->removeConvRecipient($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, GroupeMessage>
     */
    public function getGroupeMessages(): Collection
    {
        return $this->groupeMessages;
    }

    public function addGroupeMessage(GroupeMessage $groupeMessage): static
    {
        if (!$this->groupeMessages->contains($groupeMessage)) {
            $this->groupeMessages->add($groupeMessage);
            $groupeMessage->setAuthor($this);
        }

        return $this;
    }

    public function removeGroupeMessage(GroupeMessage $groupeMessage): static
    {
        if ($this->groupeMessages->removeElement($groupeMessage)) {
            // set the owning side to null (unless already changed)
            if ($groupeMessage->getAuthor() === $this) {
                $groupeMessage->setAuthor(null);
            }
        }

        return $this;
    }



}
