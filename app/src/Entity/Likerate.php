<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LikerateRepository")
 */
class Likerate
{
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Photo", inversedBy="likerates")
     * @ORM\JoinColumn(nullable=false, name="photo_id", referencedColumnName="id")
     */
    private $photo;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="likerates")
     * @ORM\JoinColumn(nullable=false, name="user_id", referencedColumnName="id")
     */
    private $user;

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
     * Getter for the Photo.
     *
     * @return Photo
     */
    public function getPhoto(): ?photo
    {
        return $this->photo;
    }

    /**
     * Setter for the Photo.
     *
     * @param Photo $photo
     */
    public function setPhoto(?photo $photo):void
    {
        $this->photo = $photo;
    }

    /**
     * Getter for the User.
     *
     * @return User
     */
    public function getUser(): ?user
    {
        return $this->user;
    }

    /**
     * Setter for the User.
     *
     * @param User $user
     */
    public function setUser(?user $user):void
    {
        $this->user = $user;
    }
}
