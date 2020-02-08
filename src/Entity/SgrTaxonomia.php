<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SgrTaxonomiaRepository")
 */
class SgrTaxonomia
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
     * @ORM\OneToMany(targetEntity="App\Entity\SgrTermino", mappedBy="taxonomia", orphanRemoval=true)
     */
    private $sgrTerminos;

    public function __construct()
    {
        $this->sgrTerminos = new ArrayCollection();
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

    /**
     * @return Collection|SgrTermino[]
     */
    public function getSgrTerminos(): Collection
    {
        return $this->sgrTerminos;
    }

    public function addSgrTermino(SgrTermino $sgrTermino): self
    {
        if (!$this->sgrTerminos->contains($sgrTermino)) {
            $this->sgrTerminos[] = $sgrTermino;
            $sgrTermino->setTaxonomia($this);
        }

        return $this;
    }

    public function removeSgrTermino(SgrTermino $sgrTermino): self
    {
        if ($this->sgrTerminos->contains($sgrTermino)) {
            $this->sgrTerminos->removeElement($sgrTermino);
            // set the owning side to null (unless already changed)
            if ($sgrTermino->getTaxonomia() === $this) {
                $sgrTermino->setTaxonomia(null);
            }
        }

        return $this;
    }

    public function __toString(){

        return $this->nombre;
    }
}
