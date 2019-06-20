<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Photo.
 *
 * @ORM\Entity(repositoryClass="App\Repository\PhotoRepository")
 *

 */
class Photo
{
    /**
     * Use constants to define configuration options that rarely change instead
     * of specifying them in app/config/config.yml.
     * See http://symfony.com/doc/current/best_practices/configuration.html#constants-vs-configuration-options
     *
     * @constant int NUMBER_OF_ITEMS
     */
    const NUMBER_OF_ITEMS = 4;
    /**
     * Primary key.
     *
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(
     *     name="id",
     *     type="integer",
     *     nullable=false,
     *     options={"unsigned"=true},
     * )
     */
    private $id;



    /**
     * Publication Date.
     *
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     *
     * @ORM\Column(type="datetime")
     *
     * @Assert\DateTime
     */
    private $publication_date;

    /**
     * Description.
     *
     * @var string
     *
     * @ORM\Column(
     *     type="string",
     *     length=255,
     *     nullable=true
     * )
     */
    private $description;

    /**
     * Camera Specification.
     *
     * @var string
     *
     * @ORM\Column(
     *     type="string",
     *     length=255,
     *     nullable=true
     * )
     */
    private $camera_specification;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="photos")
     * @ORM\JoinColumn(nullable=false, name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="photo", orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Likerate", mappedBy="photo", orphanRemoval=true)
     */
    private $likerates;

    /**
     * Tags.
     *
     * @var array
     *
     * @ORM\ManyToMany(
     *     targetEntity="App\Entity\Tag",
     *     inversedBy="photos",
     * )
     * @ORM\JoinTable(name="photos_tags")
     */
    private $tags;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\File", inversedBy="photo", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $file;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->likerates = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    /**
     * Getter for the Id.
     *
     * @return int|null Result
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * Getter for the Publication Date.
     *
     * @return \DateTimeInterface|null Publication Date
     */
    public function getPublicationDate(): ?\DateTimeInterface
    {
        return $this->publication_date;
    }

    /**
     * Setter for Publication Date.
     *
     * @param \DateTimeInterface $publication_date Publication Date
     */
    public function setPublicationDate(\DateTimeInterface $publication_date)
    {
        $this->publication_date = $publication_date;
    }

    /**
     * Getter for Description.
     *
     * @return string|null Description
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Setter for Description.
     *
     * @param string $description Description
     */
    public function setDescription(?string $description)
    {
        $this->description = $description;
    }

    /**
     * Getter for Camera Specification.
     *
     * @return string|null Camera Specification
     */
    public function getCameraSpecification(): ?string
    {
        return $this->camera_specification;
    }

    /**
     * Setter for Camera Specification.
     *
     * @param string $camera_specification Camera Specification
     */
    public function setCameraSpecification(?string $camera_specification)
    {
        $this->camera_specification = $camera_specification;
    }

    /**
     * @return user|null
     */
    public function getUser(): ?user
    {
        return $this->user;
    }

    /**
     * @param user|null $user
     */
    public function setUser(?user $user):void
    {
        $this->user = $user;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    /**
     * @param Comment $comment
     * @return Photo
     */
    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setPhoto($this);
        }

        return $this;
    }

    /**
     * @param Comment $comment
     * @return Photo
     */
    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getPhoto() === $this) {
                $comment->setPhoto(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Likerate[]
     */
    public function getLikerates(): Collection
    {
        return $this->likerates;
    }

    /**
     * @param Likerate $likerate
     * @return Photo
     */
    public function addLikerate(Likerate $likerate): self
    {
        if (!$this->likerates->contains($likerate)) {
            $this->likerates[] = $likerate;
            $likerate->setPhoto($this);
        }

        return $this;
    }

    /**
     * @param Likerate $likerate
     * @return Photo
     */
    public function removeLikerate(Likerate $likerate): self
    {
        if ($this->likerates->contains($likerate)) {
            $this->likerates->removeElement($likerate);
            // set the owning side to null (unless already changed)
            if ($likerate->getPhoto() === $this) {
                $likerate->setPhoto(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    /**
     * @param tag $tag
     * @return Photo
     */
    public function addTag(tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    /**
     * @param tag $tag
     * @return Photo
     */
    public function removeTag(tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
        }

        return $this;
    }

    /**
     * @return file|null
     */
    public function getFile(): ?file
    {
        return $this->file;
    }

    /**
     * @param file $file
     * @return Photo
     */
    public function setFile(file $file): self
    {
        $this->file = $file;

        return $this;
    }

}
