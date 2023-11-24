<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_client = null;

    #[ORM\Column]
    private ?string $first_name = null;

    #[ORM\Column]
    private ?string $document = null;
    #[ORM\Column]
    private ?string $rg = null;

    #[ORM\Column (type:"date")]
    private  $date_of_birth = null;

    #[ORM\Column]
    private ?string $last_name = null;

    public function getIdClient(): ?int
    {
        return $this->id_client;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    /**
     * @param string|null $first_name
     */
    public function setFirstName(?string $first_name): void
    {
        $this->first_name = $first_name;
    }

    /**
     * @return string|null
     */
    public function getDocument(): ?string
    {
        return $this->document;
    }

    /**
     * @param string|null $document
     */
    public function setDocument(?string $document): void
    {
        $this->document = $document;
    }

    /**
     * @return string|null
     */
    public function getRg(): ?string
    {
        return $this->rg;
    }

    /**
     * @param string|null $rg
     */
    public function setRg(?string $rg): void
    {
        $this->rg = $rg;
    }

    /**
     * @return null
     */
    public function getDateOfBirth()
    {
        return $this->date_of_birth;
    }

    /**
     * @param null $date_of_birth
     */
    public function setDateOfBirth($date_of_birth): void
    {
        $this->date_of_birth = $date_of_birth;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    /**
     * @param string|null $last_name
     */
    public function setLastName(?string $last_name): void
    {
        $this->last_name = $last_name;
    }

}
