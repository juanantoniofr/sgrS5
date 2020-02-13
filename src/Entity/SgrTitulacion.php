<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SgrTitulacionRepository")
 */
class SgrTitulacion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $codigo;

    /**
     * @ORM\Column(type="string", length=510)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tipo;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SgrAsignatura", mappedBy="sgrTitulacion")
     */
    private $asignaturas;

    public function __construct()
    {
        $this->asignaturas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodigo(): ?string
    {
        return $this->codigo;
    }

    public function setCodigo(string $codigo): self
    {
        $this->codigo = $codigo;

        return $this;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getTipo(): ?string
    {
        return $this->tipo;
    }

    public function setTipo(?string $tipo): self
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * @return Collection|SgrAsignatura[]
     */
    public function getAsignaturas(): Collection
    {
        return $this->asignaturas;
    }

    public function addAsignatura(SgrAsignatura $asignatura): self
    {
        if (!$this->asignaturas->contains($asignatura)) {
            $this->asignaturas[] = $asignatura;
            $asignatura->setSgrTitulacion($this);
        }

        return $this;
    }

    public function removeAsignatura(SgrAsignatura $asignatura): self
    {
        if ($this->asignaturas->contains($asignatura)) {
            $this->asignaturas->removeElement($asignatura);
            // set the owning side to null (unless already changed)
            if ($asignatura->getSgrTitulacion() === $this) {
                $asignatura->setSgrTitulacion(null);
            }
        }

        return $this;
    }

    public function __toString(){

        return $this->nombre;
    }
}
