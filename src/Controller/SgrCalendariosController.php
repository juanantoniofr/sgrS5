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
       * @Route("/calendarios.html", name="sgr_calendarios_index", methods={"GET","POST"})
    */
    public function index(Request $request,SgrEspacioRepository $sgrEspacioRepository, sgrFechasEventoRepository $sgrFechasEventoRepository, sgrTerminoRepository $sgrTerminoRepository)
    {

    	$form = $this->createForm(SgrFiltersSgrEventosType::class);
        $form->handleRequest($request);

        $data = $form->getData();
        //dump($data);
        //dump($data['f_inicio']);
        //dump($data['f_fin']);
        $begin = date_create_from_format('d/m/Y H:i', $data['f_inicio'] . '00:00', new \DateTimeZone('Europe/Madrid'));
        $end = date_create_from_format('d/m/Y H:i', $data['f_fin'] . '00:00', new \DateTimeZone('Europe/Madrid'));
        //dump($begin);
        //dump($end);
        $sgrFechasEvento = $sgrFechasEventoRepository->findBetween($begin, $end);
        //dump($sgrFechasEvento);
        //exit;
		$termino  =  2;// id de 'Aula de Docencia';
        
        //Search by termino
		$sgrEspacios = $sgrEspacioRepository->findByFilters($termino);
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

       	
     	return $this->render( 'sgr_calendarios/index.html.twig',[ 
        		'aCalendarios' => $aCalendarios,
        		'numDaysView' => (int) $begin->diff($end)->format('%d'),
        		'form'       => $form->createView(),
        	]
    	); 
    }

}