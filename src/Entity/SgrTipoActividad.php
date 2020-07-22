<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SgrTipoActividadRepository")
 */
class SgrTipoActividad
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
    private $actividad;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $descripcion;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $color;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SgrEvento", mappedBy="actividad")
     */
    private $eventos;

    public function __construct()
    {
        $this->eventos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getActividad(): ?string
    {
        return $this->actividad;
    }

    public function setActividad(string $actividad): self
    {
        $this->actividad = $actividad;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

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
            $evento->setActividad($this);
        }

        return $this;
    }

    public function removeEvento(SgrEvento $evento): self
    {
        if ($this->eventos->contains($evento)) {
            $this->eventos->removeElement($evento);
            // set the owning side to null (unless already changed)
            if ($evento->getActividad() === $this) {
                $evento->setActividad(null);
            }
        }

        return $this;
    }

    public function __toString(){

        return $this->actividad;
    }
}
