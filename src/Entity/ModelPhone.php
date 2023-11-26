<?php

namespace App\Entity;

use App\Repository\ModelPhoneRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ModelPhoneRepository::class)]
class ModelPhone
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_model_phone = null;

    #[ORM\Column]
    private ?string $title = null;

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?\DateTime $created = null;

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return \DateTime|null
     */
    public function getCreated(): ?\DateTime
    {
        return $this->created;
    }

    /**
     * @param \DateTime|null $created
     */
    public function setCreated(?\DateTime $created): void
    {
        $this->created = $created;
    }

    public function getIdModelPhone(): ?int
    {
        return $this->id_model_phone;
    }

}
