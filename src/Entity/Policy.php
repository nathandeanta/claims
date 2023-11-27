<?php

namespace App\Entity;

use App\Repository\PolicyRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PolicyRepository::class)]
class Policy
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_policy = null;

    #[ORM\Column]
    private mixed $number = null;

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?\DateTime $created = null;

    #[ORM\ManyToOne(targetEntity: Client::class)]
    #[ORM\JoinColumn(name: "id_client", referencedColumnName: "id_client"),]
    private ?Client $client = null;
    #[ORM\ManyToOne(targetEntity: TypeRoofing::class)]
    #[ORM\JoinColumn(name: "id_type_roofing", referencedColumnName: "id_type_roofing"),]
    private ?TypeRoofing $typeRoofing = null;

    #[ORM\ManyToOne(targetEntity: ModelPhone::class)]
    #[ORM\JoinColumn(name: "id_model_phone", referencedColumnName: "id_model_phone"),]
    private ?ModelPhone $modelPhone = null;

    #[ORM\ManyToOne(targetEntity: Color::class)]
    #[ORM\JoinColumn(name: "id_color", referencedColumnName: "id_color")]
    private ?Color $color = null;

    #[ORM\ManyToOne(targetEntity: Capacity::class)]
    #[ORM\JoinColumn(name: "id_capacity", referencedColumnName: "id_capacity")]
    private ?Capacity $capacity = null;

    /**
     * @return int|null
     */
    public function getIdPolicy(): ?int
    {
        return $this->id_policy;
    }


    /**
     * @return mixed
     */
    public function getNumber(): mixed
    {
        return $this->number;
    }

    /**
     * @param mixed $number
     */
    public function setNumber(mixed $number): void
    {
        $this->number = $number;
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

    /**
     * @return Client|null
     */
    public function getClient(): ?Client
    {
        return $this->client;
    }

    /**
     * @param Client|null $client
     */
    public function setClient(?Client $client): void
    {
        $this->client = $client;
    }

    /**
     * @return TypeRoofing|null
     */
    public function getTypeRoofing(): ?TypeRoofing
    {
        return $this->typeRoofing;
    }

    /**
     * @param TypeRoofing|null $typeRoofing
     */
    public function setTypeRoofing(?TypeRoofing $typeRoofing): void
    {
        $this->typeRoofing = $typeRoofing;
    }

    /**
     * @return ModelPhone|null
     */
    public function getModelPhone(): ?ModelPhone
    {
        return $this->modelPhone;
    }

    /**
     * @param ModelPhone|null $modelPhone
     */
    public function setModelPhone(?ModelPhone $modelPhone): void
    {
        $this->modelPhone = $modelPhone;
    }

    /**
     * @return Color|null
     */
    public function getColor(): ?Color
    {
        return $this->color;
    }

    /**
     * @param Color|null $color
     */
    public function setColor(?Color $color): void
    {
        $this->color = $color;
    }

    /**
     * @return Capacity|null
     */
    public function getCapacity(): ?Capacity
    {
        return $this->capacity;
    }

    /**
     * @param Capacity|null $capacity
     */
    public function setCapacity(?Capacity $capacity): void
    {
        $this->capacity = $capacity;
    }


}
