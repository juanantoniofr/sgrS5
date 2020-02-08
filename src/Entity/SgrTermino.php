<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SgrTerminoRepository")
 */
class SgrTermino
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
     * @ORM\Column(type="text", nullable=true)
     */
    private $descripcion;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SgrTaxonomia", inversedBy="sgrTerminos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $taxonomia;



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

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getTaxonomia(): ?SgrTaxonomia
    {
        return $this->taxonomia;
    }

    public function setTaxonomia(?SgrTaxonomia $taxonomia): self
    {
        $this->taxonomia = $taxonomia;

        return $this;
    }

    public function __toString(){

        return $this->nombre;
    }

}
