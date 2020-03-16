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

		$addPeriod = true;
		foreach ($this->periods as $period) {
			if ( $period['evento']->getId() == $sgrEvento->getId() )
				$addPeriod = false;
		}
		
		if ($addPeriod){
			$begin = $f_inicio->getFecha()->setTime($sgrEvento->getHInicio()->format('H'), $sgrEvento->getHInicio()->format('i'));
		
			$end = clone $begin;
        	$end->setTime($sgrEvento->getHFin()->format('H'), $sgrEvento->getHFin()->format('i'));
        	$interval = new \DateInterval('P1D');
        	$this->periods->add(['evento' => $sgrEvento, 'datePeriod' => new \DatePeriod($begin, $interval, $end) ]);
        }
        return $this->periods;
	}

	public function getPeriods(){

		return $this->periods;
	}

}