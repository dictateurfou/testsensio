<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TrickRepository")
 */
class Trick
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\Column(type="json")
     */
    private $videoList = [];

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $edited;

    /**
     * @ORM\Column(type="datetime")
     */
    private $edited_at;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Image", mappedBy="trick",cascade={"persist","remove"})
     */
    private $imageList;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Discussion", mappedBy="trick")
     */
    private $discussions;

    public function __construct()
    {
        $this->imageList = new ArrayCollection();
        $this->discussions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getVideoList(): ?array
    {
        return $this->videoList;
    }

    public function setVideoList(array $videoList): self
    {
        $this->videoList = $videoList;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getEdited(): ?string
    {
        return $this->edited;
    }

    public function setEdited(string $edited): self
    {
        $this->edited = $edited;

        return $this;
    }

    public function getEditedAt(): ?\DateTimeInterface
    {
        return $this->edited_at;
    }

    public function setEditedAt(\DateTimeInterface $edited_at): self
    {
        $this->edited_at = $edited_at;

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImageList(): Collection
    {
        return $this->imageList;
    }

    public function addImageList(Image $imageList): self
    {
        if (!$this->imageList->contains($imageList)) {
            $this->imageList[] = $imageList;
            $imageList->setTrick($this);
        }

        return $this;
    }

    public function removeImageList(Image $imageList): self
    {
        if ($this->imageList->contains($imageList)) {
            $this->imageList->removeElement($imageList);
            // set the owning side to null (unless already changed)
            if ($imageList->getTrick() === $this) {
                $imageList->setTrick(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Discussion[]
     */
    public function getDiscussions(): Collection
    {
        return $this->discussions;
    }

    public function addDiscussion(Discussion $discussion): self
    {
        if (!$this->discussions->contains($discussion)) {
            $this->discussions[] = $discussion;
            $discussion->setTrick($this);
        }

        return $this;
    }

    public function removeDiscussion(Discussion $discussion): self
    {
        if ($this->discussions->contains($discussion)) {
            $this->discussions->removeElement($discussion);
            // set the owning side to null (unless already changed)
            if ($discussion->getTrick() === $this) {
                $discussion->setTrick(null);
            }
        }

        return $this;
    }
}
