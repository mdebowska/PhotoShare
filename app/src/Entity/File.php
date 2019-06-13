<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class File.
 *
 * @ORM\Entity(repositoryClass="App\Repository\PhotoRepository")
 * @ORM\Table(
 *     name="file",
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(
 *              columns={"source"},
 *          ),
 *     },
 * )
 *
 * @UniqueEntity(
 *     fields={"source"}
 * )
 */
class File
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $source;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Photo", mappedBy="file", cascade={"persist", "remove"})
     */
    private $photo;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getSource(): ?string
    {
        return $this->source;
    }

    /**
     * @param string $source
     */
    public function setSource(string $source): void
    {
        $this->source = $source;
    }

    /**
     * @return Photo|null
     */
    public function getPhoto(): ?Photo
    {
        return $this->photo;
    }

    /**
     * @param Photo $photo
     * @return File
     */
    public function setPhoto(Photo $photo): self
    {
        $this->photo = $photo;

        // set the owning side of the relation if necessary
        if ($this !== $photo->getFile()) {
            $photo->setFile($this);
        }

        return $this;
    }
}
