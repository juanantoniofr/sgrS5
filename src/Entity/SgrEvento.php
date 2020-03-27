<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

//use App\Validator\Constraints as SgrAssert; (custom constrait)
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SgrEventoRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class SgrEvento
{
    const H_INICIO_MIN = '08:00';
    const H_FIN_MAX = '21:30';
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      min = 0,
     *      max = 50,
     *      minMessage = "El título debe tener más de {{ limit }} caracteres",
     *      maxMessage = "El título puede tener como máximo {{ limit }} caracteres",
     *      allowEmptyString = false
     * )
     */
    private $titulo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $estado;

    /**
     * @ORM\Column(type="date")
     * @Assert\LessThanOrEqual(propertyPath="f_fin",message="Debe ser menor que fecha hasta")
    */
    private $f_inicio;

    /**
     * @ORM\Column(type="date")
     * @Assert\GreaterThanOrEqual(propertyPath="f_inicio",
            message="Debe ser igual o mayor que fecha hasta"
            )
     */
    private $f_fin;
    
    /**
     * @ORM\Column(type="time")
     * @Assert\Expression(
     *     "this.getHInicio() < this.getHFin()",
     *     message="Hora inicio debe ser menor que hora fin"
     * )
     */
    private $h_inicio;

    /**
     * @ORM\Column(type="time")
     */
    private $h_fin;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SgrUser", inversedBy="eventos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SgrEspacio", inversedBy="eventos")
     */
    private $espacio;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SgrAsignatura", inversedBy="eventos")
     */
    private $asignatura;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SgrProfesor", inversedBy="eventos")
     */
    private $profesor;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SgrGrupoAsignatura", inversedBy="eventos")
     */
    private $grupoAsignatura;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SgrTitulacion", inversedBy="eventos")
     */
    private $titulacion;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SgrTipoActividad", inversedBy="eventos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $actividad;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SgrFechasEvento", mappedBy="evento", orphanRemoval=true)
     */
    private $fechas;

    /**
     * @ORM\Column(type="array")
     */
    private $dias = [];

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
        $this->createdAt = new \DateTime();
    }

    public function __construct()
    {
        $this->fechas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): self
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getEstado(): ?string
    {
        return $this->estado;
    }

    public function setEstado(string $estado): self
    {
        $this->estado = $estado;

        return $this;
    }

    public function getFInicio(): ?\DateTime
    {
        return $this->f_inicio;
    }

    public function setFInicio(\DateTime $f_inicio): self
    {
        $this->f_inicio = $f_inicio;

        return $this;
    }

    public function getFFin(): ?\DateTime
    {
        return $this->f_fin;
    }

    public function setFFin(\DateTime $f_fin): self
    {
        $this->f_fin = $f_fin;

        return $this;
    }

    public function getHInicio(): ?\DateTime
    {
        return $this->h_inicio;
    }

    public function setHInicio(\DateTime $h_inicio): self
    {
        $this->h_inicio = $h_inicio;

        return $this;
    }

    public function getHFin(): ?\DateTime
    {
        return $this->h_fin;
    }

    public function setHFin(\DateTime $h_fin): self
    {
        $this->h_fin = $h_fin;

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

    public function setUpdatedAt(): self
    {
        $this->updatedAt = new \DateTime("now");//$updatedAt;

        return $this;
    }

    public function getUser(): ?SgrUser
    {
        return $this->user;
    }

    public function setUser(?SgrUser $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getEspacio(): ?SgrEspacio
    {
        return $this->espacio;
    }

    public function setEspacio(?SgrEspacio $espacio): self
    {
        $this->espacio = $espacio;

        return $this;
    }

    public function getAsignatura(): ?SgrAsignatura
    {
        return $this->asignatura;
    }

    public function setAsignatura(?SgrAsignatura $asignatura): self
    {
        $this->asignatura = $asignatura;

        return $this;
    }

    public function getProfesor(): ?SgrProfesor
    {
        return $this->profesor;
    }

    public function setProfesor(?SgrProfesor $profesor): self
    {
        $this->profesor = $profesor;

        return $this;
    }

    public function getGrupoAsignatura(): ?SgrGrupoAsignatura
    {
        return $this->grupoAsignatura;
    }

    public function setGrupoAsignatura(?SgrGrupoAsignatura $grupoAsignatura): self
    {
        $this->grupoAsignatura = $grupoAsignatura;

        return $this;
    }

    public function getTitulacion(): ?SgrTitulacion
    {
        return $this->titulacion;
    }

    public function setTitulacion(?SgrTitulacion $titulacion): self
    {
        $this->titulacion = $titulacion;

        return $this;
    }

    public function getActividad(): ?SgrTipoActividad
    {
        return $this->actividad;
    }

    public function setActividad(?SgrTipoActividad $actividad): self
    {
        $this->actividad = $actividad;

        return $this;
    }

    /**
     * @return Collection|SgrFechasEvento[]
     */
    public function getFechas(): Collection
    {
        return $this->fechas;
    }

    
    public function addFecha(SgrFechasEvento $fecha): self
    {
        if (!$this->fechas->contains($fecha)) {
            $this->fechas[] = $fecha;
            $fecha->setEvento($this);
        }

        return $this;
    }

    public function removeFecha(SgrFechasEvento $fecha): self
    {
        if ($this->fechas->contains($fecha)) {
            $this->fechas->removeElement($fecha);
            // set the owning side to null (unless already changed)
            if ($fecha->getEvento() === $this) {
                $fecha->setEvento(null);
            }
        }

        return $this;
    }

    public function contains(\DateTime $date){

        return $this->fechas->contains($date);
    }

    public function getDias(): ?array
    {
        return $this->dias;
    }

    public function setDias(array $dias): self
    {
        $this->dias = $dias;

        return $this;
    }

    public function __toString(){

        return $this->titulo;
    }

    public function getDiasStringFormat(){
        
        $d_es = [ ];
        $dias = [ 'Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
        
        foreach ($this->getDias() as $i) {
            $d_es[] = $dias[$i];
        }
        
        return $d_es;
    }    

}