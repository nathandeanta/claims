<?php

namespace App\Entity;

use App\Repository\RoofingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoofingRepository::class)]
class Roofing
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_roofing = null;

    #[ORM\ManyToOne(targetEntity: Policy::class)]
    #[ORM\JoinColumn(name: "id_policy", referencedColumnName: "id_policy"),]
    private ?Policy $policy = null;

    #[ORM\ManyToOne(targetEntity: TypeRoofing::class)]
    #[ORM\JoinColumn(name: "id_type_roofing", referencedColumnName: "id_type_roofing"),]
    private ?TypeRoofing $typeRoofing = null;

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?\DateTime $created = null;

    /**
     * @return Policy|null
     */
    public function getPolicy(): ?Policy
    {
        return $this->policy;
    }

    /**
     * @param Policy|null $policy
     */
    public function setPolicy(?Policy $policy): void
    {
        $this->policy = $policy;
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

    public function getIdRoofing(): ?int
    {
        return $this->id_roofing;
    }

    public function setIdRoofing(int $id_roofing): static
    {
        $this->id_roofing = $id_roofing;

        return $this;
    }

}
