<?php

namespace App\Entity;

use App\Repository\PasswordRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PasswordRepository::class)]
class Password
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_password = null;

    #[ORM\Column]
    private ?string $code= null;

    #[ORM\Column]
    private ?\DateTime $expired= null;

    #[ORM\Column]
    private ?\DateTime $created= null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $submit= null;

    #[ORM\ManyToOne(targetEntity: Client::class)]
    #[ORM\JoinColumn(name: "id_client", referencedColumnName: "id_client"),]
    private ?Client $client = null;

    public function getIdPassword(): ?int
    {
        return $this->id_password;
    }

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string|null $code
     */
    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    /**
     * @return \DateTime|null
     */
    public function getExpired(): ?\DateTime
    {
        return $this->expired;
    }

    /**
     * @param \DateTime|null $expired
     */
    public function setExpired(?\DateTime $expired): void
    {
        $this->expired = $expired;
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
     * @return \DateTime|null
     */
    public function getSubmit(): ?\DateTime
    {
        return $this->submit;
    }

    /**
     * @param \DateTime|null $submit
     */
    public function setSubmit(?\DateTime $submit): void
    {
        $this->submit = $submit;
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

}
