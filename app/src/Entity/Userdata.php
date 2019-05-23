<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserdataRepository")
 */
class Userdata
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
     * Name.
     *
     * @ORM\Column(type="string", length=45, nullable=true)
     *
     * @Assert\Length(
     *     max="45",
     * )
     */
    private $name;

    /**
     * Surname.
     *
     * @ORM\Column(type="string", length=45, nullable=true)
     *
     * @Assert\Length(
     *     max="45",
     * )
     */
    private $surname;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="userdata", cascade={"persist", "remove"})
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
     * Getter for the Name.
     *
     * @return string|null Name
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Setter for the Name.
     *
     * @param string $name Name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * Getter for the Surname.
     *
     * @return string|null Surname
     */
    public function getSurname(): ?string
    {
        return $this->surname;
    }

    /**
     * Setter for the Surname.
     *
     * @param string $surname Surname
     */
    public function setSurname(?string $surname): void
    {
        $this->surname = $surname;
    }

    /**
     * Getter for the User.
     *
     * @return string|null User
     */
    public function getUser(): ?user
    {
        return $this->user;
    }

    /**
     * Setter for the User.
     *
     * @param int $user User
     */
    public function setUser(user $user)
    {
        $this->user = $user;
    }
}
