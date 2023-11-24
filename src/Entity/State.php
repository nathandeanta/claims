<?php

namespace App\Entity;

use App\Repository\StateRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StateRepository::class)]
class State
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_state = null;

    #[ORM\Column]
    private ?string $name;

    #[ORM\Column]
    private ?string $acronym = null;

    #[ORM\Column]
    private ?int $id_capital = null;

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
    public function getAcronym(): ?string
    {
        return $this->acronym;
    }

    /**
     * @param string|null $acronym
     */
    public function setAcronym(?string $acronym): void
    {
        $this->acronym = $acronym;
    }

    /**
     * @return int|null
     */
    public function getIdCapital(): ?int
    {
        return $this->id_capital;
    }

    /**
     * @param int|null $id_capital
     */
    public function setIdCapital(?int $id_capital): void
    {
        $this->id_capital = $id_capital;
    }
    
    public function getIdState(): ?int
    {
        return $this->id_state;
    }

}
