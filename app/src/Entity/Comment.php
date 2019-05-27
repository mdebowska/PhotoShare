<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 */
class Comment
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
     * Text.
     *
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank
     * @Assert\Length(
     *     max="255",
     * )
     */
    private $text;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\user", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\photo", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $photo;

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
     * Getter for Text.
     *
     * @return string|null Text
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * Setter for Text.
     *
     * @param string $text Text
     */
    public function setText(?string $text)
    {
        $this->text = $text;
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
     * @return user|null
     */
    public function getUser(): ?user
    {
        return $this->user;
    }

    /**
     * @param user|null $user
     * @return Comment
     */
    public function setUser(?user $user): void
    {
        $this->user = $user;
    }

    /**
     * @return photo|null
     */
    public function getPhoto(): ?photo
    {
        return $this->photo;
    }

    /**
     * @param photo|null $photo
     */
    public function setPhoto(?photo $photo): void
    {
        $this->photo = $photo;
    }
}
