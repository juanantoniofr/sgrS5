<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SgrEspacioRepository")
 */
class SgrEspacio
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
     * @ORM\Column(type="text", nullable=true)
     */
    private $acl;

    /**
     * @ORM\Column(type="integer")
     */
    private $aforo;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $medios = [];

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SgrTaxonomiaEspacio", inversedBy="sgrEspacios")
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

    public function getAcl(): ?string
    {
        return $this->acl;
    }

    public function setAcl(?string $acl): self
    {
        $this->acl = $acl;

        return $this;
    }

    public function getAforo(): ?int
    {
        return $this->aforo;
    }

    public function setAforo(int $aforo): self
    {
        $this->aforo = $aforo;

        return $this;
    }

    public function getMedios(): ?array
    {
        return $this->medios;
    }

    public function setMedios(?array $medios): self
    {
        $this->medios = $medios;

        return $this;
    }

    public function getTaxonomia(): ?SgrTaxonomiaEspacio
    {
        return $this->taxonomia;
    }

    public function setTaxonomia(?SgrTaxonomiaEspacio $taxonomia): self
    {
        $this->taxonomia = $taxonomia;

        return $this;
    }
}
