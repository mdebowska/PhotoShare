<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PhotoRepository")
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
     * @ORM\Column(type="string", length=255)
     */
    private $source;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\user", inversedBy="photos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Like", mappedBy="photo", orphanRemoval=true)
     */
    private $likes;

    public function __construct()
    {
        $this->likes = new ArrayCollection();
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
     * Getter for the Source.
     *
     * @return string|null Result
     */
    public function getSource(): ?string
    {
        return $this->source;
    }

    /**
     * Setter for Source.
     *
     * @param string $source Source
     */
    public function setSource(string $source)
    {
        $this->source = $source;
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
     * @return Collection|Like[]
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    /**
     * @param Like $like
     */
    public function addLike(Like $like): void
    {
        if (!$this->likes->contains($like)) {
            $this->likes[] = $like;
            $like->setPhoto($this);
        }
    }

    /**
     * @param Like $like
     */
    public function removeLike(Like $like): void
    {
        if ($this->likes->contains($like)) {
            $this->likes->removeElement($like);
            // set the owning side to null (unless already changed)
            if ($like->getPhoto() === $this) {
                $like->setPhoto(null);
            }
        }
    }
}
