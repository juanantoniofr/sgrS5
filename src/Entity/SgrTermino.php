<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SgrEspacio", mappedBy="termino")
     */
    private $sgrEspacios;

    public function __construct()
    {
        $this->sgrEspacios = new ArrayCollection();
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

    /**
     * @return Collection|SgrEspacio[]
     */
    public function getSgrEspacios(): Collection
    {
        return $this->sgrEspacios;
    }

    public function addSgrEspacio(SgrEspacio $sgrEspacio): self
    {
        if (!$this->sgrEspacios->contains($sgrEspacio)) {
            $this->sgrEspacios[] = $sgrEspacio;
            $sgrEspacio->setTermino($this);
        }

        return $this;
    }

    public function removeSgrEspacio(SgrEspacio $sgrEspacio): self
    {
        if ($this->sgrEspacios->contains($sgrEspacio)) {
            $this->sgrEspacios->removeElement($sgrEspacio);
            // set the owning side to null (unless already changed)
            if ($sgrEspacio->getTermino() === $this) {
                $sgrEspacio->setTermino(null);
            }
        }

        return $this;
    }

     public function __toString(){

        return $this->nombre;
    }
}
