<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Atencioneventos
 *
 * @ORM\Table(name="atencionEventos")
 * @ORM\Entity
 */
class Atencioneventos
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $createdAt = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $updatedAt = 'CURRENT_TIMESTAMP';

    /**
     * @var string
     *
     * @ORM\Column(name="evento_idSerie", type="string", length=100, nullable=false)
     */
    private $eventoIdserie;

    /**
     * @var int
     *
     * @ORM\Column(name="evento_id", type="integer", nullable=false, options={"unsigned"=true})
     */
    private $eventoId;

    /**
     * @var int
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false, options={"unsigned"=true})
     */
    private $userId;

    /**
     * @var int
     *
     * @ORM\Column(name="tecnico_id", type="integer", nullable=false, options={"unsigned"=true})
     */
    private $tecnicoId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="momento", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $momento = 'CURRENT_TIMESTAMP';

    /**
     * @var string
     *
     * @ORM\Column(name="observaciones", type="text", length=65535, nullable=false)
     */
    private $observaciones;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getEventoIdserie(): ?string
    {
        return $this->eventoIdserie;
    }

    public function setEventoIdserie(string $eventoIdserie): self
    {
        $this->eventoIdserie = $eventoIdserie;

        return $this;
    }

    public function getEventoId(): ?int
    {
        return $this->eventoId;
    }

    public function setEventoId(int $eventoId): self
    {
        $this->eventoId = $eventoId;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getTecnicoId(): ?int
    {
        return $this->tecnicoId;
    }

    public function setTecnicoId(int $tecnicoId): self
    {
        $this->tecnicoId = $tecnicoId;

        return $this;
    }

    public function getMomento(): ?\DateTimeInterface
    {
        return $this->momento;
    }

    public function setMomento(\DateTimeInterface $momento): self
    {
        $this->momento = $momento;

        return $this;
    }

    public function getObservaciones(): ?string
    {
        return $this->observaciones;
    }

    public function setObservaciones(string $observaciones): self
    {
        $this->observaciones = $observaciones;

        return $this;
    }


}
