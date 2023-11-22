<?php

namespace App\Entity;

use App\Repository\GroupConversationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinTable;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: GroupConversationRepository::class)]
class GroupConversation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('groupmessage:read')]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: Profile::class, inversedBy: 'adminGroupConversation')]
    #[Groups('groupmessage:read')]
    private Collection $convCreator;

    #[JoinTable(name: 'group_recipient_conv_profile')]
    #[ORM\ManyToMany(targetEntity: Profile::class, inversedBy: 'recipientGroupConversation')]
    #[Groups('groupmessage:read')]
    private Collection $convRecipient;

    #[ORM\OneToMany(mappedBy: 'groupConversation', targetEntity: GroupeMessage::class, orphanRemoval: true)]
    private Collection $groupeMessages;

    public function __construct()
    {
        $this->convCreator = new ArrayCollection();
        $this->convRecipient = new ArrayCollection();
        $this->groupeMessages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Profile>
     */
    public function getConvCreator(): Collection
    {
        return $this->convCreator;
    }

    public function addConvCreator(Profile $convCreator): static
    {
        if (!$this->convCreator->contains($convCreator)) {
            $this->convCreator->add($convCreator);
        }

        return $this;
    }

    public function removeConvCreator(Profile $convCreator): static
    {
        $this->convCreator->removeElement($convCreator);

        return $this;
    }

    /**
     * @return Collection<int, Profile>
     */
    public function getConvRecipient(): Collection
    {
        return $this->convRecipient;
    }

    public function addConvRecipient(Profile $convRecipient): static
    {
        if (!$this->convRecipient->contains($convRecipient)) {
            $this->convRecipient->add($convRecipient);
        }

        return $this;
    }

    public function removeConvRecipient(Profile $convRecipient): static
    {
        $this->convRecipient->removeElement($convRecipient);

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
            $groupeMessage->setGroupConversation($this);
        }

        return $this;
    }

    public function removeGroupeMessage(GroupeMessage $groupeMessage): static
    {
        if ($this->groupeMessages->removeElement($groupeMessage)) {
            // set the owning side to null (unless already changed)
            if ($groupeMessage->getGroupConversation() === $this) {
                $groupeMessage->setGroupConversation(null);
            }
        }

        return $this;
    }
}
