<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Users
 *
 * @ORM\Table(name="users", uniqueConstraints={@ORM\UniqueConstraint(name="users_username_unique", columns={"username"}), @ORM\UniqueConstraint(name="dni", columns={"dni"})})
 * @ORM\Entity
 */
class Users
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

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
     * @var string|null
     *
     * @ORM\Column(name="dni", type="string", length=12, nullable=true)
     */
    private $dni;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=100, nullable=false)
     */
    private $username;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="caducidad", type="date", nullable=true)
     */
    private $caducidad;

    /**
     * @var string
     *
     * @ORM\Column(name="capacidad", type="string", length=100, nullable=false, options={"comment"="1 -> usuarios (alumno), 2 -> usuario avanzado (PDI), 3 -> técnico (PAS), 4 -> root, 5 -> validador"})
     */
    private $capacidad = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="nombre", type="string", length=512, nullable=true)
     */
    private $nombre;

    /**
     * @var string|null
     *
     * @ORM\Column(name="apellidos", type="string", length=512, nullable=true)
     */
    private $apellidos;

    /**
     * @var string|null
     *
     * @ORM\Column(name="email", type="string", length=512, nullable=true)
     */
    private $email;

    /**
     * @var string|null
     *
     * @ORM\Column(name="colectivo", type="string", length=512, nullable=true, options={"default"="Invitado"})
     */
    private $colectivo = 'Invitado';

    /**
     * @var string|null
     *
     * @ORM\Column(name="tratamiento", type="string", length=64, nullable=true)
     */
    private $tratamiento;

    /**
     * @var string|null
     *
     * @ORM\Column(name="telefono", type="string", length=32, nullable=true)
     */
    private $telefono;

    /**
     * @var string|null
     *
     * @ORM\Column(name="remember_token", type="string", length=100, nullable=true)
     */
    private $rememberToken;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="estado", type="boolean", nullable=true)
     */
    private $estado = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="observaciones", type="string", length=512, nullable=false)
     */
    private $observaciones = '';

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     */
    private $password;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDni(): ?string
    {
        return $this->dni;
    }

    public function setDni(?string $dni): self
    {
        $this->dni = $dni;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getCaducidad(): ?\DateTimeInterface
    {
        return $this->caducidad;
    }

    public function setCaducidad(?\DateTimeInterface $caducidad): self
    {
        $this->caducidad = $caducidad;

        return $this;
    }

    public function getCapacidad(): ?string
    {
        return $this->capacidad;
    }

    public function setCapacidad(string $capacidad): self
    {
        $this->capacidad = $capacidad;

        return $this;
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

    public function getApellidos(): ?string
    {
        return $this->apellidos;
    }

    public function setApellidos(?string $apellidos): self
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getColectivo(): ?string
    {
        return $this->colectivo;
    }

    public function setColectivo(?string $colectivo): self
    {
        $this->colectivo = $colectivo;

        return $this;
    }

    public function getTratamiento(): ?string
    {
        return $this->tratamiento;
    }

    public function setTratamiento(?string $tratamiento): self
    {
        $this->tratamiento = $tratamiento;

        return $this;
    }

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function setTelefono(?string $telefono): self
    {
        $this->telefono = $telefono;

        return $this;
    }

    public function getRememberToken(): ?string
    {
        return $this->rememberToken;
    }

    public function setRememberToken(?string $rememberToken): self
    {
        $this->rememberToken = $rememberToken;

        return $this;
    }

    public function getEstado(): ?bool
    {
        return $this->estado;
    }

    public function setEstado(?bool $estado): self
    {
        $this->estado = $estado;

        return $this;
    }

    public function getObservaciones(): ?string
    {
        return $this->observaciones;
    }

    public function setObservaciones(string $observaciones): self
    {
        $this->observaciones = $observaciones;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }


}
