<?php
//src/Service/Calendario.php
namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Entity\SgrEspacio;
use App\Entity\SgrEvento;
use App\Entity\SgrFechasEvento;


class Calendario extends AbstractController{

	private $sgrEspacio;

	private $periods; 

	//private $eventos;

	public function __construct(){

		$this->periods = new ArrayCollection();
		//$this->eventos = new ArrayCollection(); 

		//$this->eventos->set('evento',new ArrayCollection());
		//$this->eventos->set('periods', new ArrayCollection());
		
		return $this;
	}

	public function setSgrEspacio(SgrEspacio $sgrEspacio){

		return $this->sgrEspacio =  $sgrEspacio;
	}

	public function getSgrEspacio(){

		return $this->sgrEspacio;
	}

	public function setPeriodsByDay(SgrEvento $sgrEvento, SgrFechasEvento $f_inicio, \DateTime $h_inicio, \DateTime $h_fin)
	{

		//dump($f_inicio->getFecha());
		
		$begin = $f_inicio->getFecha()->setTime($h_inicio->format('H'), $h_inicio->format('i'));
		
		$end = clone $begin;
        $end->setTime($h_fin->format('H'), $h_fin->format('i'));
        $interval = new \DateInterval('P1D');
        
        //dump(new \DatePeriod($begin, $interval, $end));
        //exit;

        //return $this->eventos->get($sgrEvento_id)->add(new \DatePeriod($begin, $interval, $end)); 
        
        return $this->periods->add(['evento' => $sgrEvento, 'datePeriod' => new \DatePeriod($begin, $interval, $end) ]) ;
	}

	/*public function setEvento(SgrEvento $sgrEvento){

		if ($this->eventos->containsKey($sgrEvento->getId()))
			return $this->eventos->get($sgrEvento->getId())->get('evento')->add([ $sgrEvento, 'peridos' => [] ]);

		return $this->eventos->set($sgrEvento->getId(), [ 'evento' => $sgrEvento, 'period' => [] ] );
		//if (!$this->eventos->get('evento')->contains($sgrEvento))
		//	return $this->eventos->get('evento')->add([ $sgrEvento, new ArrayCollection() ]);
	
		return '';
        
	}*/

	public function getPeriods(){

		return $this->periods;
	}


	//número de minutos entre h_inicio y h_fin 
	public function duration(\DatePeriod $period){

		//StartDate es igual a EndDate => entoces tenemos la diferrencia en horas...
		$hours = $period->getStartDate()->diff($period->getEndDate())->format('%h');
		$minutes = $period->getStartDate()->diff($period->getEndDate())->format('%i');

		return ($hours * 60) + $minutes;
	}


}