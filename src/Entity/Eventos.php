<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Eventos
 *
 * @ORM\Table(name="eventos", indexes={@ORM\Index(name="eventos_user_id_foreign", columns={"user_id"}), @ORM\Index(name="recurso_id", columns={"recurso_id"})})
 * @ORM\Entity
 */
class Eventos
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
     * @var string
     *
     * @ORM\Column(name="titulo", type="string", length=250, nullable=false)
     */
    private $titulo;

    /**
     * @var string
     *
     * @ORM\Column(name="actividad", type="string", length=255, nullable=false)
     */
    private $actividad;

    /**
     * @var string|null
     *
     * @ORM\Column(name="asignatura", type="string", length=256, nullable=true)
     */
    private $asignatura;

    /**
     * @var string|null
     *
     * @ORM\Column(name="profesor", type="string", length=256, nullable=true)
     */
    private $profesor;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="fechaFin", type="date", nullable=true)
     */
    private $fechafin;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="fechaInicio", type="date", nullable=true)
     */
    private $fechainicio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="horaInicio", type="time", nullable=false)
     */
    private $horainicio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="horaFin", type="time", nullable=false)
     */
    private $horafin;

    /**
     * @var int|null
     *
     * @ORM\Column(name="repeticion", type="integer", nullable=true)
     */
    private $repeticion;

    /**
     * @var string
     *
     * @ORM\Column(name="diasSemana", type="string", length=256, nullable=false)
     */
    private $diassemana;

    /**
     * @var string
     *
     * @ORM\Column(name="evento_id", type="string", length=100, nullable=false)
     */
    private $eventoId;

    /**
     * @var int|null
     *
     * @ORM\Column(name="reservadoPor_id", type="integer", nullable=true, options={"unsigned"=true})
     */
    private $reservadoporId;

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
     * @var \DateTime|null
     *
     * @ORM\Column(name="fechaEvento", type="date", nullable=true)
     */
    private $fechaevento;

    /**
     * @var string
     *
     * @ORM\Column(name="dia", type="string", length=255, nullable=false)
     */
    private $dia;

    /**
     * @var string
     *
     * @ORM\Column(name="estado", type="string", length=256, nullable=false)
     */
    private $estado;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="atendida", type="boolean", nullable=true)
     */
    private $atendida = '0';

    /**
     * @var \Espacio
     *
     * @ORM\ManyToOne(targetEntity="Espacio")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="recurso_id", referencedColumnName="id")
     * })
     */
    private $recurso;

    /**
     * @var \Users
     *
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

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

    public function getActividad(): ?string
    {
        return $this->actividad;
    }

    public function setActividad(string $actividad): self
    {
        $this->actividad = $actividad;

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

    public function getProfesor(): ?string
    {
        return $this->profesor;
    }

    public function setProfesor(?string $profesor): self
    {
        $this->profesor = $profesor;

        return $this;
    }

    public function getFechafin(): ?\DateTimeInterface
    {
        return $this->fechafin;
    }

    public function setFechafin(?\DateTimeInterface $fechafin): self
    {
        $this->fechafin = $fechafin;

        return $this;
    }

    public function getFechainicio(): ?\DateTimeInterface
    {
        return $this->fechainicio;
    }

    public function setFechainicio(?\DateTimeInterface $fechainicio): self
    {
        $this->fechainicio = $fechainicio;

        return $this;
    }

    public function getHorainicio(): ?\DateTimeInterface
    {
        return $this->horainicio;
    }

    public function setHorainicio(\DateTimeInterface $horainicio): self
    {
        $this->horainicio = $horainicio;

        return $this;
    }

    public function getHorafin(): ?\DateTimeInterface
    {
        return $this->horafin;
    }

    public function setHorafin(\DateTimeInterface $horafin): self
    {
        $this->horafin = $horafin;

        return $this;
    }

    public function getRepeticion(): ?int
    {
        return $this->repeticion;
    }

    public function setRepeticion(?int $repeticion): self
    {
        $this->repeticion = $repeticion;

        return $this;
    }

    public function getDiassemana(): ?string
    {
        return $this->diassemana;
    }

    public function setDiassemana(string $diassemana): self
    {
        $this->diassemana = $diassemana;

        return $this;
    }

    public function getEventoId(): ?string
    {
        return $this->eventoId;
    }

    public function setEventoId(string $eventoId): self
    {
        $this->eventoId = $eventoId;

        return $this;
    }

    public function getReservadoporId(): ?int
    {
        return $this->reservadoporId;
    }

    public function setReservadoporId(?int $reservadoporId): self
    {
        $this->reservadoporId = $reservadoporId;

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

    public function getFechaevento(): ?\DateTimeInterface
    {
        return $this->fechaevento;
    }

    public function setFechaevento(?\DateTimeInterface $fechaevento): self
    {
        $this->fechaevento = $fechaevento;

        return $this;
    }

    public function getDia(): ?string
    {
        return $this->dia;
    }

    public function setDia(string $dia): self
    {
        $this->dia = $dia;

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

    public function getAtendida(): ?bool
    {
        return $this->atendida;
    }

    public function setAtendida(?bool $atendida): self
    {
        $this->atendida = $atendida;

        return $this;
    }

    public function getRecurso(): ?Espacio
    {
        return $this->recurso;
    }

    public function setRecurso(?Espacio $recurso): self
    {
        $this->recurso = $recurso;

        return $this;
    }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): self
    {
        $this->user = $user;

        return $this;
    }


}
