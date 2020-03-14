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


	public function __construct(){

		$this->periods = new ArrayCollection();		
		return $this;
	}

	public function setSgrEspacio(SgrEspacio $sgrEspacio){

		return $this->sgrEspacio =  $sgrEspacio;
	}

	public function getSgrEspacio(){

		return $this->sgrEspacio;
	}

	public function setPeriodsByDay(SgrEvento $sgrEvento, SgrFechasEvento $f_inicio)
	{

		$begin = $f_inicio->getFecha()->setTime($sgrEvento->getHInicio()->format('H'), $sgrEvento->getHInicio()->format('i'));
		
		$end = clone $begin;
        $end->setTime($sgrEvento->getHFin()->format('H'), $sgrEvento->getHFin()->format('i'));
        $interval = new \DateInterval('P1D');
        
        return $this->periods->add(['evento' => $sgrEvento, 'datePeriod' => new \DatePeriod($begin, $interval, $end) ]) ;
	}

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