<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SgrGrupoAsignaturaRepository")
 */
class SgrGrupoAsignatura
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
    private $nombre;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $capacidad;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SgrAsignatura", inversedBy="grupos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sgrAsignatura;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\SgrProfesor", mappedBy="grupos")
     */
    private $sgrProfesors;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SgrEvento", mappedBy="grupoAsignatura")
     */
    private $eventos;

    public function __construct()
    {
        $this->sgrProfesors = new ArrayCollection();
        $this->eventos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCapacidad(): ?int
    {
        return $this->capacidad;
    }

    public function setCapacidad(?int $capacidad): self
    {
        $this->capacidad = $capacidad;

        return $this;
    }

    public function getSgrAsignatura(): ?SgrAsignatura
    {
        return $this->sgrAsignatura;
    }

    public function setSgrAsignatura(?SgrAsignatura $sgrAsignatura): self
    {
        $this->sgrAsignatura = $sgrAsignatura;

        return $this;
    }

    /**
     * @return Collection|SgrProfesor[]
     */
    public function getSgrProfesors(): Collection
    {
        return $this->sgrProfesors;
    }

    public function addSgrProfesor(SgrProfesor $sgrProfesor): self
    {
        if (!$this->sgrProfesors->contains($sgrProfesor)) {
            $this->sgrProfesors[] = $sgrProfesor;
            $sgrProfesor->addGrupo($this);
        }

        return $this;
    }

    public function removeSgrProfesor(SgrProfesor $sgrProfesor): self
    {
        if ($this->sgrProfesors->contains($sgrProfesor)) {
            $this->sgrProfesors->removeElement($sgrProfesor);
            $sgrProfesor->removeGrupo($this);
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
            $evento->setGrupoAsignatura($this);
        }

        return $this;
    }

    public function removeEvento(SgrEvento $evento): self
    {
        if ($this->eventos->contains($evento)) {
            $this->eventos->removeElement($evento);
            // set the owning side to null (unless already changed)
            if ($evento->getGrupoAsignatura() === $this) {
                $evento->setGrupoAsignatura(null);
            }
        }

        return $this;
    }

    public function __toString(){

        return $this->nombre;
    }

}
