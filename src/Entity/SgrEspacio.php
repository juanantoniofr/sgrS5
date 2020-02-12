<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\Column(type="integer")
     */
    private $aforo;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $AforoExamen;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\SgrEquipamiento", inversedBy="sgrEspacios")
     */
    private $mediosDisponibles;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SgrTermino", inversedBy="sgrEspacios")
     * @ORM\JoinColumn(nullable=false)
     */
    private $termino;

    public function __construct()
    {
        $this->mediosDisponibles = new ArrayCollection();
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

    public function getAforo(): ?int
    {
        return $this->aforo;
    }

    public function setAforo(int $aforo): self
    {
        $this->aforo = $aforo;

        return $this;
    }

    public function getAforoExamen(): ?int
    {
        return $this->AforoExamen;
    }

    public function setAforoExamen(?int $AforoExamen): self
    {
        $this->AforoExamen = $AforoExamen;

        return $this;
    }

    /**
     * @return Collection|SgrEquipamiento[]
     */
    public function getMediosDisponibles(): Collection
    {
        return $this->mediosDisponibles;
    }

    public function addMediosDisponible(SgrEquipamiento $mediosDisponible): self
    {
        if (!$this->mediosDisponibles->contains($mediosDisponible)) {
            $this->mediosDisponibles[] = $mediosDisponible;
        }

        return $this;
    }

    public function removeMediosDisponible(SgrEquipamiento $mediosDisponible): self
    {
        if ($this->mediosDisponibles->contains($mediosDisponible)) {
            $this->mediosDisponibles->removeElement($mediosDisponible);
        }

        return $this;
    }
    
    public function __toString(){

        return $this->nombre;
    }

    public function getTermino(): ?SgrTermino
    {
        return $this->termino;
    }

    public function setTermino(?SgrTermino $termino): self
    {
        $this->termino = $termino;

        return $this;
    }
   
}