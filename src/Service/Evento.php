<?php
//src/Service/Evento.php
namespace App\Service;

use App\Entity\SgrEvento;
use App\Entity\SgrFechasEvento;
use App\Entity\SgrProfesor;
use App\Entity\SgrAsignatura;
use App\Entity\SgrTitulacion;
use App\Entity\SgrTipoActividad;
use App\Entity\SgrGrupoAsignatura;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Evento extends AbstractController
{

	private $sgrEvento;

    public function setEvento(SgrEvento $sgrEvento)
    {

    	$this->sgrEvento = $sgrEvento;
    	return $this;
    }

    public function getEvento()
    {

    	return $this->sgrEvento;
    }

    public function setFechaDesde($f_desde)
    {
        $this->sgrEvento->setFInicio( date_create_from_format('d/m/Y', $f_desde, new \DateTimeZone('Europe/Madrid')) );

        return $this;
    }

    public function setFechaHasta($f_hasta)
    {

        $this->sgrEvento->setFFin( date_create_from_format('d/m/Y', $f_hasta, new \DateTimeZone('Europe/Madrid')) );

        return $this;
    }

    public function setHoraInicio($h_inicio)
    {

        $this->sgrEvento->setHInicio( date_create_from_format('H:i', $h_inicio, new \DateTimeZone('Europe/Madrid')) );
        return $this;
    }

    public function setHoraFin($h_fin)
    {

        $this->sgrEvento->setHFin( date_create_from_format('H:i', $h_fin, new \DateTimeZone('Europe/Madrid')) );
        return $this;
    }

    public function setDias($dia)
    {
        $this->sgrEvento->setDias([ $dia ]);
        return $this;
    }

    public function setProfesor($nombreProfesor, $entityManager, $repositoryProfesor){

        $profesor = $repositoryProfesor->findOneBy([ 'nombre' => $nombreProfesor ]);
        if($profesor)
            $this->sgrEvento->setProfesor($profesor);
        else
        {
            $profesor = new SgrProfesor;
            $profesor->setNombre($nombreProfesor);
            $entityManager->persist($profesor);
            $entityManager->flush();
            $this->sgrEvento->setProfesor($profesor);
        }
        return $this;
    }

    public function setTitulacion ($codigoAsignatura, $repositoryTitulacion)
    {

        $this->sgrEvento->setTitulacion($repositoryTitulacion->findOneBy([ 'codigo' => substr($codigoAsignatura,0,3) ]));

        return $this;
    }

    public function setAsignatura($codigoAsignatura, $nombreAsignatura, $cuatrimestre, $entityManager, $repositoryAsignatura, $repositoryTitulacion)
    {

        $asignatura = $repositoryAsignatura->findOneBy([ 'codigo' => $codigoAsignatura ]);
        if($asignatura){
            
            $this->sgrEvento->setAsignatura($asignatura);
        }
        else
        {
            $asignatura = new SgrAsignatura;
            $asignatura->setCodigo($codigoAsignatura);
            $asignatura->setNombre($nombreAsignatura);
            $asignatura->setCuatrimestre($cuatrimestre);
            $asignatura->setSgrTitulacion($repositoryTitulacion->findOneBy([ 'codigo' => substr($codigoAsignatura,0,3) ])); //LANZAR EXCEPTION SI NO EXISTE TITULACIÓN
            $entityManager->persist($asignatura);
            $entityManager->flush();

            $this->sgrEvento->setAsignatura($asignatura);
        }

        return $this;
    }

    public function setGrupo($nombreGrupo, $entityManager, $repositoryGrupoAsignatura)
    {

        $asignatura = $this->sgrEvento->getAsignatura();
        
        $grupo = $repositoryGrupoAsignatura->findOneBy([ 'sgrAsignatura' => $asignatura->getId() ]);
        //dump($grupo);

        if($grupo){
            $this->sgrEvento->setGrupoAsignatura($grupo);
            $asignatura->addGrupo($grupo);
            $entityManager->persist($asignatura);
            $entityManager->flush();   
        }
        else{
            //
            $grupo = new SgrGrupoAsignatura;
            $grupo->setNombre($nombreGrupo);
            $grupo->setSgrAsignatura($asignatura);
            $grupo->addSgrProfesor($this->sgrEvento->getProfesor());
            
            $entityManager->persist($grupo);
            $entityManager->flush();
        }

        return $this;
    }

    /**
     * Comprueba si $this->sgrEvento (evento candidato a salvar a BD -edit/new en SgrEventoController or index en SgrUploadCSVController) solapa con algún evento ya en BD
     * @return ArrayCollection(sgrFechasEvento)|vacio si no hay solapamientos
    */
    public function hasSolape(){

        $solapes = new ArrayCollection();
        //Array de objetos DateTime entre from f_inicio to f_fin 
        $dateTimeFechasEvento = $this->ToArray($this->calculateFechasEvento());
        //Initialize

        //Array de object SgrFechaEventos
        $result = $this->getDoctrine()->getRepository(SgrFechasEvento::class)->findFechasWithOutEventoId($dateTimeFechasEvento,$this->sgrEvento->getId());
        //dump($result);
        foreach ($result as $fecha) {
        //$result -> array con las fechas que coincide con alguna de las fechas del evento $this->sgrEvento
            //$this->sgrEvento->initialize();//->getEspacio()->initialize();
            //dump( $fecha->getEvento());//->getEspacio() );
            //dump( $this->sgrEvento);//->getEspacio() );
            if (
                //Si coinciden en el mismo recurso (espacio)
                $fecha->getEvento()->getEspacio() == $this->sgrEvento->getEspacio()
                &&
                (
                    //Y si Solapan las horas de realización del evento
                    ($this->sgrEvento->getHInicio()->format('H:i') < $fecha->getEvento()->getHInicio()->format('H:i') &&
                     $this->sgrEvento->getHFin()->format('H:i') > $fecha->getEvento()->getHInicio()->format('H:i')
                    )
                    ||
                    ($this->sgrEvento->getHInicio()->format('H:i') >= $fecha->getEvento()->getHInicio()->format('H:i') &&
                    $this->sgrEvento->getHInicio()->format('H:i') < $fecha->getEvento()->getHFin()->format('H:i')
                    )
                )
                )
            {
                //$solapa = true;
                /*if ($flash_errors)
                    $this->addFlash(
                            'danger',
                            'Espacio ocupado el día ' . $fecha->getFecha()->format('d-m-Y')
                    );
                */
                //dump($fecha);
                $solapes->add($fecha); //ArrayCollection de objetos SgrFechasEvento
            }
                
        }
        //dump($solapes);
        //exit;
        return $solapes;
    }

    /**
     * Calcula las fechas entre f_inicio y f_fin para cada día de la semana en el array días
     *
     * @return Array Object DateTime 
    */
    public function calculateFechasEvento()
    {

        $fechas = [];

        $start = $this->sgrEvento->getFInicio();
        $end = clone $this->sgrEvento->getFFin();
        $end->add(new \DateInterval('PT24H'));
        $interval = new \DateInterval('P1D');
        $period = new \DatePeriod($start, $interval, $end);
        //dump($period);
        //dump(!$this->sgrEvento->getDias());
        if ( !$this->sgrEvento->getDias() ){
            $this->setDias([ $start->format('w') ]);
        }

        foreach ($period as $day) {

            if ( in_array($day->format('w'), $this->sgrEvento->getDias()) ) 
                $fechas[] = $day;

        }

        return $fechas;
    }

    public function calculateDias($fechasEvento)
    {
        //concordar dias[] con fechasEventos
        $dias = array();
        $fechasEvento->forAll(function($index, $fechaEvento) use (&$dias){
            
            if ( !in_array($fechaEvento->format('w'), $dias) )
                    $dias[] = $fechaEvento->format('w');
                    return true;
            });

        return $dias;
    }

    public function ToArray(Array $fechas)
    {
        
        $result = [];
        foreach ($fechas as $fecha) {
            $result[] = $fecha->format('Y-m-d');
        }

        return $result;
    }   

}