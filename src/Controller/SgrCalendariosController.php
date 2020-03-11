<?php
// src/Controller/SgrCalendariosController.php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Common\Collections\ArrayCollection;

use App\Repository\SgrEspacioRepository;
use App\Repository\SgrTerminoRepository;
use App\Repository\SgrFechasEventoRepository;

class SgrCalendariosController extends AbstractController
{
    

    /**
       * @Route("/calendarios.html", name="sgr_calendarios_index")
    */
    public function index(SgrEspacioRepository $sgrEspacioRepository, sgrFechasEventoRepository $sgrFechasEventoRepository, sgrTerminoRepository $sgrTerminoRepository)
    {


        $termino  =  2;// id de 'Aula de Docencia';
        $f = '10/02/2020';
        //dump($f);

        //Search by termino

        //dump($sgrEspacioRepository->findByFilters($termino));
        $sgrEspacios = $sgrEspacioRepository->findByFilters($termino);
        $aResult = new ArrayCollection(); 
        //dump($aResult);   
        
        foreach ($sgrEspacios as $sgrEspacio){ 
            //add all sgrEspacios
        	$aResult->set( $sgrEspacio->getId(), new ArrayCollection( [ $sgrEspacio , 'rangos' => new ArrayCollection() ]) );
       	}
        
        //dump($aResult);
        //exit;
        //search by fecha  
        //$fecha = new \DateTimeZone('Europe/Madrid');
        if ( $fecha = date_create_from_format('d/m/Y', $f, new \DateTimeZone('Europe/Madrid')) )//->getId();
        {    
                       
        	$solapes = $sgrFechasEventoRepository->findByFecha($fecha);
        	if ( $solapes )
        	{
            	//$solape es un objeto sgrFechasEvento
            	foreach ( $solapes as $solape ) {
				
					$sgrEspacio = $solape->getEvento()->getEspacio();
                	if( $aResult->containsKey($sgrEspacio->getId())){
                		$sgr_evento = $solape->getEvento();
                		$h_inicio = $sgr_evento->getHInicio()->format('H:i');
                		
                		$hi = date_create_from_format('H:i', $h_inicio, new \DateTimeZone('Europe/Madrid'));
                		//dump($hi);
                		$h_fin = $sgr_evento->getHFin()->format('H:i');
                		
                		$hf = date_create_from_format('H:i', $h_fin, new \DateTimeZone('Europe/Madrid'));
                		//dump($hf);
                		$step = 30; //30 minutos
                		//dump($step);
                		$diff = $hi->diff($hf);
                		$diffInMinutes = $diff->format('%h') * 60 + $diff->format('%i');
                		$rangeHorario = [ 'hi' => $hi->format('U'), 'numSteps' => $diffInMinutes / $step ];
                		//dump($rangeHorario);
                    	$aResult->get($sgrEspacio->getId())->get('rangos')->add($rangeHorario);    
                	}
            	}
        	}
        }
        //dump($aResult);
        //exit;


        return $this->render( 'sgr_calendarios/index.html.twig',
        	[ 
        		'sgr_espacios' => $aResult,
        		//'sgr_espacios' => $sgrTerminoRepository->findAll(),
        	]
    	);
    }
}