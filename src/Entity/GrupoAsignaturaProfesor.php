<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GrupoAsignaturaProfesor
 *
 * @ORM\Table(name="grupo_asignatura_profesor")
 * @ORM\Entity
 */
class GrupoAsignaturaProfesor
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
     * @var int|null
     *
     * @ORM\Column(name="grupo_asignatura_id", type="integer", nullable=true)
     */
    private $grupoAsignaturaId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="profesor_id", type="string", length=45, nullable=true)
     */
    private $profesorId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGrupoAsignaturaId(): ?int
    {
        return $this->grupoAsignaturaId;
    }

    public function setGrupoAsignaturaId(?int $grupoAsignaturaId): self
    {
        $this->grupoAsignaturaId = $grupoAsignaturaId;

        return $this;
    }

    public function getProfesorId(): ?string
    {
        return $this->profesorId;
    }

    public function setProfesorId(?string $profesorId): self
    {
        $this->profesorId = $profesorId;

        return $this;
    }


}
