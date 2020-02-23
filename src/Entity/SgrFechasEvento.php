<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SgrFechasEventoRepository")
 */
class SgrFechasEvento
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SgrEvento", inversedBy="fechas",cascade={"persist"})
     * @ORM\JoinColumn(name="evento_id", referencedColumnName="id",nullable=false)
     */
    private $evento;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getEvento(): ?SgrEvento
    {
        return $this->evento;
    }

    public function setEvento(?SgrEvento $evento): self
    {
        $this->evento = $evento;

        return $this;
    }

    public function __toString(){

        return $this->fecha->format('d-m-Y');
    }
}
