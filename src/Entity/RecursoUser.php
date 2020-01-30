<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RecursoUser
 *
 * @ORM\Table(name="recurso_user", indexes={@ORM\Index(name="recurso_id", columns={"recurso_id"}), @ORM\Index(name="user_id", columns={"user_id"})})
 * @ORM\Entity
 */
class RecursoUser
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
     * @var bool
     *
     * @ORM\Column(name="mail", type="boolean", nullable=false, options={"default"="1"})
     */
    private $mail = '1';

    /**
     * @var \Espacio
     *
     * @ORM\ManyToOne(targetEntity="Espacio")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="recurso_id", referencedColumnName="id")
     * })
     */
    private $recurso;

    /**
     * @var \Users
     *
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMail(): ?bool
    {
        return $this->mail;
    }

    public function setMail(bool $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getRecurso(): ?Espacio
    {
        return $this->recurso;
    }

    public function setRecurso(?Espacio $recurso): self
    {
        $this->recurso = $recurso;

        return $this;
    }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): self
    {
        $this->user = $user;

        return $this;
    }


}
