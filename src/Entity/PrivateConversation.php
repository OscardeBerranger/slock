<?php

namespace App\Entity;

use App\Repository\PrivateConversationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PrivateConversationRepository::class)]
class PrivateConversation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'createdPrivateConversations')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['conv:read', 'message:read'])]
    private ?Profile $convCreator = null;

    #[ORM\ManyToOne(inversedBy: 'receivedPrivateConversation')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['conv:read', 'message:read'])]

    private ?Profile $convRecipient = null;

    #[ORM\OneToMany(mappedBy: 'privateConversation', targetEntity: PrivateMessage::class, orphanRemoval: true)]
    #[Groups('conv:read')]
    private Collection $messages;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getConvCreator(): ?Profile
    {
        return $this->convCreator;
    }

    public function setConvCreator(?Profile $convCreator): static
    {
        $this->convCreator = $convCreator;

        return $this;
    }

    public function getConvRecipient(): ?Profile
    {
        return $this->convRecipient;
    }

    public function setConvRecipient(?Profile $convRecipient): static
    {
        $this->convRecipient = $convRecipient;

        return $this;
    }

    /**
     * @return Collection<int, PrivateMessage>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(PrivateMessage $message): static
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setPrivateConversation($this);
        }

        return $this;
    }

    public function removeMessage(PrivateMessage $message): static
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getPrivateConversation() === $this) {
                $message->setPrivateConversation(null);
            }
        }

        return $this;
    }
}
