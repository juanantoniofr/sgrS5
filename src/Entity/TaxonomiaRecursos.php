<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TaxonomiaRecursos
 *
 * @ORM\Table(name="taxonomia_recursos")
 * @ORM\Entity
 */
class TaxonomiaRecursos
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
     * @var string|null
     *
     * @ORM\Column(name="nombre", type="string", length=256, nullable=true, options={"comment"="Agrupación de recursos, por ejemplo, salas de informática, salas de docencia, cámaras de Kanón XL 1200...."})
     */
    private $nombre;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(?string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }


}
