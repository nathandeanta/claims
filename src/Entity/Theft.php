<?php

namespace App\Entity;

use App\Repository\TheftRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TheftRepository::class)]
class Theft
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_theft = null;

    #[ORM\Column(type:"text",length: 5000)]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTime  $date_occurrence= null;

    #[ORM\OneToOne(targetEntity: Policy::class)]
    #[ORM\JoinColumn(name: "id_policy", referencedColumnName: "id_policy")]
    private ?Policy $policy = null;

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }



    /**
     * @return \DateTime|null
     */
    public function getDateOccurrence(): ?\DateTime
    {
        return $this->date_occurrence;
    }

    /**
     * @param \DateTime|null $date_occurrence
     */
    public function setDateOccurrence(?\DateTime $date_occurrence): void
    {
        $this->date_occurrence = $date_occurrence;
    }

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

    public function getIdTheft(): ?int
    {
        return $this->id_theft;
    }


}
