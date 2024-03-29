<?php

namespace App\Entity;

use App\Repository\GroupeMessageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: GroupeMessageRepository::class)]
class GroupeMessage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("groupmessage:read")]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'groupeMessages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?GroupConversation $groupConversation = null;

    #[ORM\Column]
    #[Groups("groupmessage:read")]
    private ?\DateTimeImmutable $createdAt = null;

    #[Groups("groupmessage:read")]
    #[ORM\Column(length: 255)]
    private ?string $content = null;

    #[Groups("groupmessage:read")]
    #[ORM\ManyToOne(inversedBy: 'groupeMessages')]
    private ?Profile $author = null;

    #[ORM\OneToMany(mappedBy: 'groupeMessage', targetEntity: Image::class)]
    private Collection $images;

    #[Groups("groupmessage:read")]
    private ArrayCollection $imagesUrls;

    private array $associatedImages;

    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGroupConversation(): ?GroupConversation
    {
        return $this->groupConversation;
    }

    public function setGroupConversation(?GroupConversation $groupConversation): static
    {
        $this->groupConversation = $groupConversation;

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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

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

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setGroupeMessage($this);
        }

        return $this;
    }

    public function removeImage(Image $image): static
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getGroupeMessage() === $this) {
                $image->setGroupeMessage(null);
            }
        }

        return $this;
    }

    public function getImagesUrls(): ArrayCollection
    {
        return $this->imagesUrls;
    }

    public function setImagesUrls(ArrayCollection $imagesUrls): void
    {
        $this->imagesUrls = $imagesUrls;
    }

    public function getAssociatedImages(): array
    {
        return $this->associatedImages;
    }

    public function setAssociatedImages(array $associatedImages): void
    {
        $this->associatedImages = $associatedImages;
    }
}
