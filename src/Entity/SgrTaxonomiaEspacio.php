<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SgrTaxonomiaEspacioRepository")
 */
class SgrTaxonomiaEspacio
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
    private $desccripcion;

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

    public function getDesccripcion(): ?string
    {
        return $this->desccripcion;
    }

    public function setDesccripcion(?string $desccripcion): self
    {
        $this->desccripcion = $desccripcion;

        return $this;
    }
}
