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

        //defaults values
        $aCalendarios = new ArrayCollection();
        $begin = date_create_from_format('d/m/Y H:i', '01/09/2019 00:00', new \DateTimeZone('Europe/Madrid'));
        $end = date_create_from_format('d/m/Y H:i', '31/08/2020 00:00', new \DateTimeZone('Europe/Madrid'));
        $termino  =  2;// id de 'Aula de Docencia';
        //dump($form->isSubmitted());
        //dump($form->isValid());
        //dump($form->getErrors());

        //exit;
        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            
            if ($data['f_inicio'])
                $begin = date_create_from_format('d/m/Y H:i', $data['f_inicio'] . '00:00', new \DateTimeZone('Europe/Madrid'));
            if($data['f_fin'])
                $end = date_create_from_format('d/m/Y H:i', $data['f_fin'] . '00:00', new \DateTimeZone('Europe/Madrid'));
            if($data['termino'])
                $termino = $data['termino'];
            //dump($termino);
            //exit;
            $sgrFechasEvento = $sgrFechasEventoRepository->findBetween($begin, $end);
            $sgrEspacios = $sgrEspacioRepository->findByFilters($termino);
            //dump($end->format('Y-m-d'));
            //exit;
            
            foreach ($sgrEspacios as $sgrEspacio){ 
            	
         		$calendario = new Calendario;
         		$calendario->setSgrEspacio($sgrEspacio);
         		
         		$eventos = new ArrayCollection();
    		    foreach ($sgrFechasEvento as $sgrFechaEvento)
                {
         			
         			if ($sgrFechaEvento->getEvento()->getEspacio() == $sgrEspacio)
         				$calendario->setPeriodsByDay( $sgrFechaEvento->getEvento(), $sgrFechaEvento);
         		}
         		
         		$aCalendarios[] = $calendario;
         	}
        }
     	
        return $this->render( 'sgr_calendarios/index.html.twig',[ 
      		'aCalendarios' => $aCalendarios,
      		'numDaysView' => (int) $begin->diff($end)->format('%d'),
      		'form'       => $form->createView(),
      	]
      ); 
    }
}