<?php
//
//namespace App\Entity;
//
//use Doctrine\Common\Collections\ArrayCollection;
//use Doctrine\Common\Collections\Collection;
//use Doctrine\ORM\Mapping as ORM;
//
///**
// * @ORM\Entity(repositoryClass="App\Repository\PhotodataRepository")
// */
//class Photodata
//{
//    /**
//     * Primary key.
//     *
//     * @var int
//     *
//     * @ORM\Id
//     * @ORM\GeneratedValue
//     * @ORM\Column(
//     *     name="id",
//     *     type="integer",
//     *     nullable=false,
//     *     options={"unsigned"=true},
//     * )
//     */
//    private $id;
//
//    /**
//     * Description.
//     *
//     * @var string
//     *
//     * @ORM\Column(
//     *     type="string",
//     *     length=255,
//     *     nullable=true
//     * )
//     */
//    private $description;
//
//    /**
//     * Camera Specification.
//     *
//     * @var string
//     *
//     * @ORM\Column(
//     *     type="string",
//     *     length=255,
//     *     nullable=true
//     * )
//     */
//    private $camera_specification;
//
//    /**
//     * @ORM\OneToOne(targetEntity="App\Entity\photo", inversedBy="photodata", cascade={"persist", "remove"})
//     * @ORM\JoinColumn(nullable=false)
//     */
//    private $photo;
//
//    /**
//     * Tags.
//     *
//     * @var array
//     *
//     * @ORM\ManyToMany(
//     *     targetEntity="App\Entity\Tag",
//     *     inversedBy="photos",
//     *     orphanRemoval=true
//     * )
//     * @ORM\JoinTable(name="photos_tags")
//     */
//    private $tags;
//
//    public function __construct()
//    {
//        $this->tags = new ArrayCollection();
//    }
//
//    /**
//     * Getter for the Id.
//     *
//     * @return int|null Result
//     */
//    public function getId(): ?int
//    {
//        return $this->id;
//    }
//
//    /**
//     * Getter for Description.
//     *
//     * @return string|null Description
//     */
//    public function getDescription(): ?string
//    {
//        return $this->description;
//    }
//    /**
//     * Setter for Description.
//     *
//     * @param string $description Description
//     */
//    public function setDescription(?string $description)
//    {
//        $this->description = $description;
//    }
//    /**
//     * Getter for Camera Specification.
//     *
//     * @return string|null Camera Specification
//     */
//    public function getCameraSpecification(): ?string
//    {
//        return $this->camera_specification;
//    }
//    /**
//     * Setter for Camera Specification.
//     *
//     * @param string $camera_specification Camera Specification
//     */
//    public function setCameraSpecification(?string $camera_specification)
//    {
//        $this->camera_specification = $camera_specification;
//    }
//
//    public function getPhoto(): ?photo
//    {
//        return $this->photo;
//    }
//
//    /**
//     * @param photo $photo
//     */
//    public function setPhoto(photo $photo): void
//    {
//        $this->photo = $photo;
//    }
//
//    /**
//     * @return Collection|tag[]
//     */
//    public function getTags(): Collection
//    {
//        return $this->tags;
//    }
//
//    /**
//     * @param tag $tag
//     * @return Photodata
//     */
//    public function addTag(tag $tag): self
//    {
//        if (!$this->tags->contains($tag)) {
//            $this->tags[] = $tag;
//        }
//
//        return $this;
//    }
//
//    /**
//     * @param tag $tag
//     * @return Photodata
//     */
//    public function removeTag(tag $tag): self
//    {
//        if ($this->tags->contains($tag)) {
//            $this->tags->removeElement($tag);
//        }
//        return $this;
//    }
//}
