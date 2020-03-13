<?php
//src/Service/Calendario.php
namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Entity\SgrEspacio;
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

	public function setPeriodsByDay(SgrFechasEvento $f_inicio, \DateTime $h_inicio, \DateTime $h_fin)
	{

		//dump($f_inicio->getFecha());
		
		$begin = $f_inicio->getFecha()->setTime($h_inicio->format('H'), $h_inicio->format('i'));
		
		$end = clone $begin;
        $end->setTime($h_fin->format('H'), $h_fin->format('i'));
        $interval = new \DateInterval('P1D');
        
        //dump(new \DatePeriod($begin, $interval, $end));
        //exit;
        
        return $this->periods->add(new \DatePeriod($begin, $interval, $end)) ;
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