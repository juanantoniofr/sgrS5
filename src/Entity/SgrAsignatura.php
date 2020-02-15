<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SgrAsignaturaRepository")
 */
class SgrAsignatura
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
     * @ORM\Column(type="string", length=255)
     */
    private $cuatrimestre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $curso;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SgrTitulacion", inversedBy="asignaturas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sgrTitulacion;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SgrGrupoAsignatura", mappedBy="sgrAsignatura", orphanRemoval=true)
     */
    private $grupos;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SgrEvento", mappedBy="asignatura")
     */
    private $eventos;

    public function __construct()
    {
        $this->grupos = new ArrayCollection();
        $this->eventos = new ArrayCollection();
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

    public function getCuatrimestre(): ?string
    {
        return $this->cuatrimestre;
    }

    public function setCuatrimestre(string $cuatrimestre): self
    {
        $this->cuatrimestre = $cuatrimestre;

        return $this;
    }

    public function getCurso(): ?string
    {
        return $this->curso;
    }

    public function setCurso(string $curso): self
    {
        $this->curso = $curso;

        return $this;
    }

    public function getSgrTitulacion(): ?SgrTitulacion
    {
        return $this->sgrTitulacion;
    }

    public function setSgrTitulacion(?SgrTitulacion $sgrTitulacion): self
    {
        $this->sgrTitulacion = $sgrTitulacion;

        return $this;
    }

    /**
     * @return Collection|SgrGrupoAsignatura[]
     */
    public function getGrupos(): Collection
    {
        return $this->grupos;
    }

    public function addGrupo(SgrGrupoAsignatura $grupo): self
    {
        if (!$this->grupos->contains($grupo)) {
            $this->grupos[] = $grupo;
            $grupo->setSgrAsignatura($this);
        }

        return $this;
    }

    public function removeGrupo(SgrGrupoAsignatura $grupo): self
    {
        if ($this->grupos->contains($grupo)) {
            $this->grupos->removeElement($grupo);
            // set the owning side to null (unless already changed)
            if ($grupo->getSgrAsignatura() === $this) {
                $grupo->setSgrAsignatura(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|SgrEvento[]
     */
    public function getEventos(): Collection
    {
        return $this->eventos;
    }

    public function addEvento(SgrEvento $evento): self
    {
        if (!$this->eventos->contains($evento)) {
            $this->eventos[] = $evento;
            $evento->setAsignatura($this);
        }

        return $this;
    }

    public function removeEvento(SgrEvento $evento): self
    {
        if ($this->eventos->contains($evento)) {
            $this->eventos->removeElement($evento);
            // set the owning side to null (unless already changed)
            if ($evento->getAsignatura() === $this) {
                $evento->setAsignatura(null);
            }
        }

        return $this;
    }
}
