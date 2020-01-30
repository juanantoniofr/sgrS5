<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Fechasevento
 *
 * @ORM\Table(name="fechasEvento", indexes={@ORM\Index(name="fk_fechaEventos_1_idx", columns={"evento_id"})})
 * @ORM\Entity
 */
class Fechasevento
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
     * @var \DateTime|null
     *
     * @ORM\Column(name="fechaEvento", type="datetime", nullable=true)
     */
    private $fechaevento;

    /**
     * @var \Eventos
     *
     * @ORM\ManyToOne(targetEntity="Eventos")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="evento_id", referencedColumnName="id")
     * })
     */
    private $evento;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getEvento(): ?Eventos
    {
        return $this->evento;
    }

    public function setEvento(?Eventos $evento): self
    {
        $this->evento = $evento;

        return $this;
    }


}
