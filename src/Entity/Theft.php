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

    #[ORM\Column (nullable: true)]
    private ?\DateTime  $ai_procces= null;

    #[ORM\Column(type:"string",nullable: true)]
    private ?string $status = null;

    #[ORM\Column(type:"string",nullable: true)]
    private ?string $response = null;

    /**
     * @return \DateTime|null
     */
    public function getAiProcces(): ?\DateTime
    {
        return $this->ai_procces;
    }

    /**
     * @param \DateTime|null $ai_procces
     */
    public function setAiProcces(?\DateTime $ai_procces): void
    {
        $this->ai_procces = $ai_procces;
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string|null $status
     */
    public function setStatus(?string $status): void
    {
        $this->status = $status;
    }

    /**
     * @return string|null
     */
    public function getResponse(): ?string
    {
        return $this->response;
    }

    /**
     * @param string|null $response
     */
    public function setResponse(?string $response): void
    {
        $this->response = $response;
    }

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
