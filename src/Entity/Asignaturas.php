<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Asignaturas
 *
 * @ORM\Table(name="asignaturas", uniqueConstraints={@ORM\UniqueConstraint(name="codigo_UNIQUE", columns={"codigo"})}, indexes={@ORM\Index(name="fk_asignaturas_1_idx", columns={"titulacion_id"})})
 * @ORM\Entity
 */
class Asignaturas
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
     * @ORM\Column(name="codigo", type="string", length=45, nullable=true)
     */
    private $codigo;

    /**
     * @var string|null
     *
     * @ORM\Column(name="asignatura", type="string", length=512, nullable=true)
     */
    private $asignatura;

    /**
     * @var string|null
     *
     * @ORM\Column(name="cuatrimestre", type="string", length=45, nullable=true)
     */
    private $cuatrimestre;

    /**
     * @var string|null
     *
     * @ORM\Column(name="curso", type="string", length=45, nullable=true)
     */
    private $curso;

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
     * @var \Titulaciones
     *
     * @ORM\ManyToOne(targetEntity="Titulaciones")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="titulacion_id", referencedColumnName="id")
     * })
     */
    private $titulacion;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodigo(): ?string
    {
        return $this->codigo;
    }

    public function setCodigo(?string $codigo): self
    {
        $this->codigo = $codigo;

        return $this;
    }

    public function getAsignatura(): ?string
    {
        return $this->asignatura;
    }

    public function setAsignatura(?string $asignatura): self
    {
        $this->asignatura = $asignatura;

        return $this;
    }

    public function getCuatrimestre(): ?string
    {
        return $this->cuatrimestre;
    }

    public function setCuatrimestre(?string $cuatrimestre): self
    {
        $this->cuatrimestre = $cuatrimestre;

        return $this;
    }

    public function getCurso(): ?string
    {
        return $this->curso;
    }

    public function setCurso(?string $curso): self
    {
        $this->curso = $curso;

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

    public function getTitulacion(): ?Titulaciones
    {
        return $this->titulacion;
    }

    public function setTitulacion(?Titulaciones $titulacion): self
    {
        $this->titulacion = $titulacion;

        return $this;
    }


}
