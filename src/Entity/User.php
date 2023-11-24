<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_user = null;

    #[ORM\Column(type: "string", length: 255,nullable: true)]
    private ?string $name;
    #[ORM\Column(type: "string", length: 255,nullable: true)]
    private ?string $email;

    #[ORM\Column(type: "string", length: 255,nullable: true)]
    private ?string $password;

    #[ORM\Column(type: "string", length: 255,nullable: true)]
    private ?string $position;

    #[ORM\Column(type: "integer")]
    private ?int $admin=0;

    #[ORM\Column(type: "integer")]
    private ?int $active=0;

    public function getIdUser(): ?int
    {
        return $this->id_user;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string|null $password
     */
    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return int|null
     */
    public function getAdmin(): ?int
    {
        return $this->admin;
    }

    /**
     * @param int|null $admin
     */
    public function setAdmin(?int $admin): void
    {
        $this->admin = $admin;
    }

    /**
     * @return int|null
     */
    public function getActive(): ?int
    {
        return $this->active;
    }

    /**
     * @param int|null $active
     */
    public function setActive(?int $active): void
    {
        $this->active = $active;
    }

    /**
     * @return string|null
     */
    public function getPosition(): ?string
    {
        return $this->position;
    }

    /**
     * @param string|null $position
     */
    public function setPosition(?string $position): void
    {
        $this->position = $position;
    }

}
