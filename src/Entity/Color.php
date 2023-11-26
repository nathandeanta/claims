<?php

namespace App\Entity;

use App\Repository\ColorRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ColorRepository::class)]
class Color
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $id_color = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdColor(): ?string
    {
        return $this->id_color;
    }

    public function setIdColor(string $id_color): static
    {
        $this->id_color = $id_color;

        return $this;
    }
}
