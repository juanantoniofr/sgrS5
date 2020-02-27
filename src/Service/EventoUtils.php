<?php
//src/Service/evento.php
namespace App\Service;

use App\Entity\SgrEvento;
use App\Entity\SgrFechasEvento;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EventoUtils extends AbstractController
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

    /**
     * devuleve true si $this->sgrEvento solapa con cualquier otro evento en el mismo espacio
     * @return bool
    */
    public function solapa(){

        $solapa = false;
        //Array de objetos DateTime entre from f_inicio to f_fin 
        $dateTimeFechasEvento = $this->ToArray($this->calculateDates());
        dump($dateTimeFechasEvento);
        
        //Array de object SgrFechaEventos
        $result = $this->getDoctrine()->getRepository(SgrFechasEvento::class)->finByFechas($dateTimeFechasEvento,$this->sgrEvento->getId());
        dump($this->sgrEvento->getId());
        dump($result);
        //$result -> array con las fechas que coincide con alguna de las fechas del evento $this->sgrEvento
        foreach ($result as $fecha) {
            dump($fecha->getEvento()->getEspacio());
            //coincide el espacio??
            dump($this->sgrEvento->getHInicio());
            dump($this->sgrEvento->getHFin());
            dump($this->sgrEvento->getHInicio() == $this->sgrEvento->getHFin());
            dump($this->sgrEvento);
            if ($fecha->getEvento()->getEspacio() == $this->sgrEvento->getEspacio() &&
                $fecha->getEvento()->getHInicio()->format('H:i') <= $this->sgrEvento->getHInicio()->format('H:i') &&
                $fecha->getEvento()->getHFin()->format('H:i') > $this->sgrEvento->getHInicio()->format('H:i') ){
                $solapa = true;
                $this->addFlash(
                            'danger',
                            'Espacio ocupado el día ' . $fecha->getFecha()->format('d-m-Y')
                        );

            }
                dump($fecha->getEvento()->getEspacio() == $this->sgrEvento->getEspacio());
                dump($fecha->getEvento()->getHInicio() <= $this->sgrEvento->getHInicio());
                dump($fecha->getEvento()->getHFin()->format('H:i') > $this->sgrEvento->getHInicio()->format('H:i'));
                dump($fecha->getEvento());
                
        }
        dump($solapa);
        //exit;
        return $solapa;
    }


                //$fecha->getEvento()->getEspacio() == $this->sgrEvento->getEspacio()
                //&&
                //$fecha->getEvento()->getHInicio() <= $this->sgrEvento->getHInicio()
                //&&
                //$fecha->getEvento()->getHFin() > $this->sgrEvento->getHInicio()
    /**
     * Calcula las fechas entre f_inicio y f_fin para cada día de la semana en el array días
     *
     * @return Array Object DateTime 
    */
    public function calculateDates()
    {

        $adt = [];

        if ( !$this->sgrEvento->getDias() ){
            $weekDays[] =  $this->sgrEvento->getFInicio()->format('l'); // Monday, Tuesday, ....
        }
        else {
            $weekDays = $this->sgrEvento->getDias(); //getDias devuelve el array dias
        }
        
        //dump($weekDays);
        
        $days = [ 'Sunday', 'Monday', 'Tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        $end = $this->sgrEvento->getFFin();
        $end->modify('+1 day'); //include last day in DatePeriod
        $interval = new \DateInterval('P7D');

        $start = $this->sgrEvento->getFInicio();
        //dump($start->format('l'));
        foreach ($weekDays as $day) {
        	
        	//dump($start->format('l'));
        	//dump($days[$day]);
        	//dump($start->format('l') ==  $days[$day]);
        	if ( $start->format('l') ==  $days[$day] ){
        	       		
        		$aBegin[] = $start;
        	}
        	else {
        	
        		$newstart = clone $start;//->modify($days[$day]);
        		$aBegin[] = $newstart->modify($days[$day]);
        	}

        }
        
        foreach ($aBegin as $begin) {
        	$aPeriod[] = new \DatePeriod($begin, $interval, $end);
        }
        
        
        //dump($aPeriod);


        //Para cada dt (datetime) en el periodo entre begin y end con un incremento (intervalo) de 7 días
        foreach ($aPeriod as $period) {
        	
            foreach ($period as $dt) {
        //		dump($dt);
           		//if ( $dtdw <= $end )  $adt[] = $dtdw;
           		$adt[] = $dt;//->format('Y-m-d');
        	}
        }
        //}
       // dump($adt);
       // exit;
        return $adt;
    }

    public function ToArray(Array $fechas){
        //dump($sgrFechasEvento);
        //exit;
        $result = [];
        foreach ($fechas as $fecha) {
            $result[] = $fecha->format('Y-m-d');
        }

        return $result;

    }

}