<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SgrEventoRepository")
 */
class SgrEvento
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
    private $titulo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $estado;

    /**
     * @ORM\Column(type="boolean")
     */
    private $periodica;

    /**
     * @ORM\Column(type="date")
     */
    private $f_inicio;

    /**
     * @ORM\Column(type="date")
     */
    private $f_fin;

    /**
     * @ORM\Column(type="time")
     */
    private $h_inicio;

    /**
     * @ORM\Column(type="time")
     */
    private $h_fin;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SgrUser", inversedBy="eventos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SgrEspacio", inversedBy="eventos")
     */
    private $espacio;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SgrAsignatura", inversedBy="eventos")
     */
    private $asignatura;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SgrProfesor", inversedBy="eventos")
     */
    private $profesor;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SgrGrupoAsignatura", inversedBy="eventos")
     */
    private $grupoAsignatura;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SgrTitulacion", inversedBy="eventos")
     */
    private $titulacion;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SgrTipoActividad", inversedBy="eventos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $actividad;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): self
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getEstado(): ?string
    {
        return $this->estado;
    }

    public function setEstado(string $estado): self
    {
        $this->estado = $estado;

        return $this;
    }

    public function getPeriodica(): ?bool
    {
        return $this->periodica;
    }

    public function setPeriodica(bool $periodica): self
    {
        $this->periodica = $periodica;

        return $this;
    }

    public function getFInicio(): ?\DateTimeInterface
    {
        return $this->f_inicio;
    }

    public function setFInicio(\DateTimeInterface $f_inicio): self
    {
        $this->f_inicio = $f_inicio;

        return $this;
    }

    public function getFFin(): ?\DateTimeInterface
    {
        return $this->f_fin;
    }

    public function setFFin(\DateTimeInterface $f_fin): self
    {
        $this->f_fin = $f_fin;

        return $this;
    }

    public function getHInicio(): ?\DateTimeInterface
    {
        return $this->h_inicio;
    }

    public function setHInicio(\DateTimeInterface $h_inicio): self
    {
        $this->h_inicio = $h_inicio;

        return $this;
    }

    public function getHFin(): ?\DateTimeInterface
    {
        return $this->h_fin;
    }

    public function setHFin(\DateTimeInterface $h_fin): self
    {
        $this->h_fin = $h_fin;

        return $this;
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

    public function getUser(): ?SgrUser
    {
        return $this->user;
    }

    public function setUser(?SgrUser $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getEspacio(): ?SgrEspacio
    {
        return $this->espacio;
    }

    public function setEspacio(?SgrEspacio $espacio): self
    {
        $this->espacio = $espacio;

        return $this;
    }

    public function getAsignatura(): ?SgrAsignatura
    {
        return $this->asignatura;
    }

    public function setAsignatura(?SgrAsignatura $asignatura): self
    {
        $this->asignatura = $asignatura;

        return $this;
    }

    public function getProfesor(): ?SgrProfesor
    {
        return $this->profesor;
    }

    public function setProfesor(?SgrProfesor $profesor): self
    {
        $this->profesor = $profesor;

        return $this;
    }

    public function getGrupoAsignatura(): ?SgrGrupoAsignatura
    {
        return $this->grupoAsignatura;
    }

    public function setGrupoAsignatura(?SgrGrupoAsignatura $grupoAsignatura): self
    {
        $this->grupoAsignatura = $grupoAsignatura;

        return $this;
    }

    public function getTitulacion(): ?SgrTitulacion
    {
        return $this->titulacion;
    }

    public function setTitulacion(?SgrTitulacion $titulacion): self
    {
        $this->titulacion = $titulacion;

        return $this;
    }

    public function getActividad(): ?SgrTipoActividad
    {
        return $this->actividad;
    }

    public function setActividad(?SgrTipoActividad $actividad): self
    {
        $this->actividad = $actividad;

        return $this;
    }
}
