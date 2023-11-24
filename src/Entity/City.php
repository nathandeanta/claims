<?php

namespace App\Entity;

use App\Repository\CityRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CityRepository::class)]
class City
{
    #[ORM\Id]
    #[ORM\Column]
    private ?int $id_city = null;
    #[ORM\Column]
    private ?string $name;
    #[ORM\Column(nullable: true)]
    private ?int $id_state;

    #[ORM\Column]
    private ?int  $ddd;

    /**
     * @param int|null $id_city
     */
    public function setIdCity(?int $id_city): void
    {
        $this->id_city = $id_city;
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
     * @return int|null
     */
    public function getIdState(): ?int
    {
        return $this->id_state;
    }

    /**
     * @param int|null $id_state
     */
    public function setIdState(?int $id_state): void
    {
        $this->id_state = $id_state;
    }

    /**
     * @return int|null
     */
    public function getDdd(): ?int
    {
        return $this->ddd;
    }

    /**
     * @param int|null $ddd
     */
    public function setDdd(?int $ddd): void
    {
        $this->ddd = $ddd;
    }

}
