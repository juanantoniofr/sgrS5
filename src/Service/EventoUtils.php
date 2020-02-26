<?php
//src/Service/evento.php
namespace App\Service;

use App\Entity\SgrEvento;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EventoUtils extends AbstractController
{

	private $evento;


	public function __construct()
    {
        
    }

    public function setEvento(SgrEvento $sgrEvento)
    {
    	$this->evento = $sgrEvento;
    	return $this;
    }

    public function getEvento()
    {
    	return $this->evento;
    }

    public function solapa(){

        //Array datetime fechasEventos from f_inicio to f_fin 
        $dateTimeFechasEvento = $this->calculateDates();
        dump($dateTimeFechasEvento);
        //exit;
        $solape = false;
        foreach ($dateTimeFechasEvento as $date)
        {
            if($this->getDoctrine()->getRepository(SgrEvento::class)->getEventosContains($date,$this->evento->getEspacio()->getId()))
            {
                $solape = true;
                $this->addFlash(
                        'danger',
                        'Espacio ocupado el día ' . $date->format('d-m-Y')
                );
            }
            else {
            	$this->addFlash(
                        'success',
                        'Espacio libre el día ' . $date->format('d-m-Y')
                );
            }
        }
    return $solape;
    }

    public function calculateDates()
    {

        $adt = [];

        if ( !$this->evento->getDias() ){
            $weekDays[] =  $this->evento->getFInicio()->format('l'); // Monday, Tuesday, ....
        }
        else {
            $weekDays = $this->evento->getDias(); //getDias devuelve el array dias
        }
        
        dump($weekDays);
        
        $days = [ 'Sunday', 'Monday', 'Tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        $end = $this->evento->getFFin();
        $end->modify('+1 day'); //include last day in DatePeriod
        $interval = new \DateInterval('P7D');

        $start = $this->evento->getFInicio();
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
        
        
        dump($aPeriod);


        //Para cada dt (datetime) en el periodo entre begin y end con un incremento (intervalo) de 7 días
        foreach ($aPeriod as $period) {
        	
            foreach ($period as $dt) {
        		dump($dt);
           		//if ( $dtdw <= $end )  $adt[] = $dtdw;
           		$adt[] = $dt;
        	}
        }
        //}
        dump($adt);
        exit;
        return $adt;
    }

}