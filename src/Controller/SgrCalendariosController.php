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

/**
 * @Route("/admin/sgr/calendario")
 */
class SgrCalendariosController extends AbstractController
{
    

    /**
       * @Route("/index.html", name="sgr_calendarios_index", methods={"GET","POST"})
    */
    public function index(Request $request,SgrEspacioRepository $sgrEspacioRepository, sgrFechasEventoRepository $sgrFechasEventoRepository, sgrTerminoRepository $sgrTerminoRepository)
    {

    	$form = $this->createForm(SgrFiltersSgrEventosType::class);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            //dump($data);
            //exit;
            //filter by fechas
            if ($data['f_inicio'])
                $begin = date_create_from_format('d/m/Y H:i', $data['f_inicio'] . '00:00', new \DateTimeZone('Europe/Madrid'));
            if($data['f_fin'])
                $end = date_create_from_format('d/m/Y H:i', $data['f_fin'] . '00:00', new \DateTimeZone('Europe/Madrid'));
            
            $sgrFechasEvento = $sgrFechasEventoRepository->findBetween($begin, $end);
            //dump($sgrFechasEvento);
            //exit;
            //filter by actividad
            if( $data['actividad'] ) {

                $actividad = $data['actividad'];
                $aux = new ArrayCollection($sgrFechasEvento);    
                $aux = $aux->filter(function($a) use ($actividad) {
                    return $a->getEvento()->getActividad() == $actividad;
                });    
                $sgrFechasEvento = $aux->toArray();
                
            }
            //All espacios.
            $sgrEspacios = $sgrEspacioRepository->findAll();

            //filter by termino
            if($data['termino'])
                $sgrEspacios = $sgrEspacioRepository->findByFilters($data['termino']);
            
            //filter by espacio 
            if( !$data['espacio']->isEmpty() )
                $sgrEspacios = $sgrEspacioRepository->findBy(['id' => $data['espacio']->toArray()]);

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

        if (isset($aCalendarios))
            return $this->render( 'sgr_calendarios/index.html.twig',[ 
          		'aCalendarios' => $aCalendarios,
          		'numDaysView' => (int) $begin->diff($end)->format('%d'),
          		'form'  => $form->createView(),
                'data'  => [ 'begin' => $begin , 'end' => $end ],
          	]
          );

        return $this->render( 'sgr_calendarios/index.html.twig',[ 
                'form'  => $form->createView(),
            ]);
    }
}