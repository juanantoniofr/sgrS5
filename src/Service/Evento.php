<?php
//src/Service/Evento.php
namespace App\Service;

use App\Entity\SgrEvento;
use App\Entity\SgrFechasEvento;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

    /**
     * devuleve true si $this->sgrEvento solapa con cualquier otro evento en el mismo espacio
     * @return bool
    */
    public function solapa(){

        $solapa = false;
        //Array de objetos DateTime entre from f_inicio to f_fin 
        $dateTimeFechasEvento = $this->ToArray($this->calculateFechasEvento());
        //dump($dateTimeFechasEvento);
        
        //Array de object SgrFechaEventos
        dump($this->sgrEvento);
        //exit;
        $result = $this->getDoctrine()->getRepository(SgrFechasEvento::class)->findFechasWithOutEventoId($dateTimeFechasEvento,$this->sgrEvento->getId());
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

    /**
     * Calcula las fechas entre f_inicio y f_fin para cada día de la semana en el array días
     *
     * @return Array Object DateTime 
    */
    public function calculateFechasEvento()
    {

        $adt = [];

        if ( !$this->sgrEvento->getDias() ){
            $weekDays[] =  $this->sgrEvento->getFInicio()->format('w'); // 0=sunday, 1=Monday, 2=Tuesday, ....
        }
        else {
            $weekDays = $this->sgrEvento->getDias(); //getDias devuelve el array dias
        }
        
        $days = [ 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $start = $this->sgrEvento->getFInicio();
        $end = $this->sgrEvento->getFFin();
        if (!$end)
            $end = clone $start;
        $end->modify('+1 day'); //include last day in DatePeriod
        
        $interval = new \DateInterval('P7D');

        foreach ($weekDays as $day) {

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
        
        //Para cada dt (datetime) en el periodo entre begin y end con un incremento (intervalo) de 7 días
        foreach ($aPeriod as $period) {
        	
            foreach ($period as $dt) {
           		$adt[] = $dt;
        	}
        }
        //}
       // dump($adt);
       // exit;
        return $adt;
    }

    public function ToArray(Array $fechas){
        
        $result = [];
        foreach ($fechas as $fecha) {
            $result[] = $fecha->format('Y-m-d');
        }

        return $result;
    }   

}