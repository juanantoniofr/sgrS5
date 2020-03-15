<?php
// src/Controller/SgrCalendariosController.php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Request;

use App\Repository\SgrEspacioRepository;
use App\Repository\SgrTerminoRepository;
use App\Repository\SgrFechasEventoRepository;

use App\Form\SgrFiltersSgrEventosType;

use App\Service\Calendario;

class SgrCalendariosController extends AbstractController
{
    

    /**
       * @Route("/calendarios.html", name="sgr_calendarios_index")
    */
    public function index(Request $request,SgrEspacioRepository $sgrEspacioRepository, sgrFechasEventoRepository $sgrFechasEventoRepository, sgrTerminoRepository $sgrTerminoRepository)
    {
		$termino  =  2;// id de 'Aula de Docencia';
        $f = '13/03/2020';
        $fecha = date_create_from_format('d/m/Y', $f, new \DateTimeZone('Europe/Madrid'));
        
        //Search by termino
		$sgrEspacios = $sgrEspacioRepository->findByFilters($termino);
        $sgrFechasEvento = $sgrFechasEventoRepository->findBy( ['fecha' => $fecha] );
        foreach ($sgrEspacios as $sgrEspacio){ 
          	
       		$calendario = new Calendario;
       		$calendario->setSgrEspacio($sgrEspacio);
       		
       		$eventos = new ArrayCollection();
			foreach ($sgrFechasEvento as $sgrFechaEvento) 
			{
       			
       			if ($sgrFechaEvento->getEvento()->getEspacio() == $sgrEspacio)
       				$calendario->setPeriodsByDay( $sgrFechaEvento->getEvento(), $sgrFechaEvento, $sgrFechaEvento->getEvento()->getHInicio(), $sgrFechaEvento->getEvento()->getHFin());
       		}
       		
       		$aCalendarios[] = $calendario;
       	}

       	$form = $this->createForm(SgrFiltersSgrEventosType::class);
        $form->handleRequest($request);

     	return $this->render( 'sgr_calendarios/index.html.twig',[ 
        		'aCalendarios' => $aCalendarios,
        		'form'       => $form->createView(),
        	]
    	); 
    }

}