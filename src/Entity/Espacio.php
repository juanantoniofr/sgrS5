<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Espacio
 *
 * @ORM\Table(name="espacio", indexes={@ORM\Index(name="fk_espacio_1_idx", columns={"taxonomia_id"})})
 * @ORM\Entity
 */
class Espacio
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
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=250, nullable=false)
     */
    private $nombre;

    /**
     * @var string|null
     *
     * @ORM\Column(name="descripcion", type="text", length=65535, nullable=true)
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="acl", type="string", length=250, nullable=false)
     */
    private $acl;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="disabled", type="boolean", nullable=true)
     */
    private $disabled = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="aforomaximo", type="integer", nullable=true)
     */
    private $aforomaximo;

    /**
     * @var int|null
     *
     * @ORM\Column(name="aforoexamen", type="integer", nullable=true)
     */
    private $aforoexamen;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $createdAt = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $updatedAt = 'CURRENT_TIMESTAMP';

    /**
     * @var \TaxonomiaRecursos
     *
     * @ORM\ManyToOne(targetEntity="TaxonomiaRecursos")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="taxonomia_id", referencedColumnName="id")
     * })
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

    public function setAcl(string $acl): self
    {
        $this->acl = $acl;

        return $this;
    }

    public function getDisabled(): ?bool
    {
        return $this->disabled;
    }

    public function setDisabled(?bool $disabled): self
    {
        $this->disabled = $disabled;

        return $this;
    }

    public function getAforomaximo(): ?int
    {
        return $this->aforomaximo;
    }

    public function setAforomaximo(?int $aforomaximo): self
    {
        $this->aforomaximo = $aforomaximo;

        return $this;
    }

    public function getAforoexamen(): ?int
    {
        return $this->aforoexamen;
    }

    public function setAforoexamen(?int $aforoexamen): self
    {
        $this->aforoexamen = $aforoexamen;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getTaxonomia(): ?TaxonomiaRecursos
    {
        return $this->taxonomia;
    }

    public function setTaxonomia(?TaxonomiaRecursos $taxonomia): self
    {
        $this->taxonomia = $taxonomia;

        return $this;
    }


}
