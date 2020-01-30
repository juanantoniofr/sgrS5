<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Gruposasignatura
 *
 * @ORM\Table(name="gruposAsignatura", indexes={@ORM\Index(name="fk_gruposAsignatura_1_idx", columns={"asignatura_id"})})
 * @ORM\Entity
 */
class Gruposasignatura
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="grupo", type="string", length=512, nullable=true)
     */
    private $grupo;

    /**
     * @var string|null
     *
     * @ORM\Column(name="capacidad", type="string", length=512, nullable=true)
     */
    private $capacidad;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $createdAt = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $updatedAt = 'CURRENT_TIMESTAMP';

    /**
     * @var \Asignaturas
     *
     * @ORM\ManyToOne(targetEntity="Asignaturas")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="asignatura_id", referencedColumnName="id")
     * })
     */
    private $asignatura;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGrupo(): ?string
    {
        return $this->grupo;
    }

    public function setGrupo(?string $grupo): self
    {
        $this->grupo = $grupo;

        return $this;
    }

    public function getCapacidad(): ?string
    {
        return $this->capacidad;
    }

    public function setCapacidad(?string $capacidad): self
    {
        $this->capacidad = $capacidad;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getAsignatura(): ?Asignaturas
    {
        return $this->asignatura;
    }

    public function setAsignatura(?Asignaturas $asignatura): self
    {
        $this->asignatura = $asignatura;

        return $this;
    }


}
