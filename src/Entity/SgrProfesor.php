<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SgrProfesorRepository")
 */
class SgrProfesor
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $dni;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\SgrGrupoAsignatura", inversedBy="sgrProfesors")
     */
    private $grupos;

    public function __construct()
    {
        $this->grupos = new ArrayCollection();
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

    public function getDni(): ?string
    {
        return $this->dni;
    }

    public function setDni(?string $dni): self
    {
        $this->dni = $dni;

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
        }

        return $this;
    }

    public function removeGrupo(SgrGrupoAsignatura $grupo): self
    {
        if ($this->grupos->contains($grupo)) {
            $this->grupos->removeElement($grupo);
        }

        return $this;
    }
}