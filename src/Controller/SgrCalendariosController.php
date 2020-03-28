<?php
// src/Controller/SgrCalendariosController.php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Repository\SgrEspacioRepository;
use App\Repository\SgrTerminoRepository;
use App\Repository\SgrFechasEventoRepository;

use App\Form\SgrFiltersSgrEventosType;
use App\Form\SgrEventoType;
use App\Entity\SgrEvento;
use App\Entity\SgrFechasEvento;
use App\Service\Calendario;
use App\Service\Evento;

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
        
        //for filters calendario de eventos        
        $form = $this->createForm(SgrFiltersSgrEventosType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //dump($form->getData());

            $data = $form->getData();
            //dump($data);
            //exit;
            //filter by fechas
            if ($data['f_inicio'])
                $begin = $data['f_inicio'];
            if($data['f_fin'])
                $end = $data['f_fin'];

            
            $sgrFechasEvento = $sgrFechasEventoRepository->findBetween($begin, $end);
            //dump($sgrFechasEvento);
            
            //filter by actividad
            if( $data['actividad'])
            {
                $actividad = $data['actividad'];
                $aux = new ArrayCollection($sgrFechasEvento);
                $aux = $aux->filter(function($item) use ($actividad) {
                    return $item->getEvento()->getActividad() == $actividad;
                });    
                $sgrFechasEvento = $aux->toArray();
                
            }
            
            //filter by titulación
            if ($data['titulacion'] && $sgrFechasEvento)
            {
                $titulacion = $data['titulacion'];
                $aux = new ArrayCollection($sgrFechasEvento);
                $aux = $aux->filter(function($item) use($titulacion){
                    return $item->getEvento()->getTitulacion() == $titulacion; 
                });
                $sgrFechasEvento = $aux->toArray();
            }

            //filter by asignatura
            if ($data['asignatura'] && $sgrFechasEvento)
            {
                $asignatura = $data['asignatura'];
                $aux = new ArrayCollection($sgrFechasEvento);
                $aux = $aux->filter(function($item) use($asignatura){
                    return $item->getEvento()->getAsignatura() == $asignatura; 
                });
                $sgrFechasEvento = $aux->toArray();
            }

            //filter by profesor
            if ($data['profesor'] && $sgrFechasEvento)
            {
                $profesor = $data['profesor'];
                $aux = new ArrayCollection($sgrFechasEvento);
                $aux = $aux->filter(function($item) use($profesor){
                    return $item->getEvento()->getProfesor() == $profesor; 
                });
                $sgrFechasEvento = $aux->toArray();
            }
          
            //All espacios.
            $sgrEspacios = $sgrEspacioRepository->findAll();

            //filter by termino
            //dump($data);
            //exit;
            if($data['termino'])
                $sgrEspacios = $sgrEspacioRepository->findBy([ 'termino' => $data['termino'] ]);
            
            //filter by espacio
            //dump($data['espacio']->isEmpty());
            //exit; 
            if( !$data['espacio']->isEmpty())
            {
                $espacios = $data['espacio'];
                $sgrEspacios = ( new ArrayCollection
                  ($sgrEspacioRepository->findAll() ))->filter(function($sgrEspacio) use ($espacios) {
                    return $espacios->contains($sgrEspacio);
                }
              );//(['nombre' => $data['espacio'] ]);
            }

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

        if (isset($aCalendarios)){

            
            return $this->render( 'sgr_calendarios/index.html.twig',[ 
          		'aCalendarios' => $aCalendarios,
          		'numDaysView' => (int) $begin->diff($end)->format('%d'),
          		'form'  => $form->createView(),
                'data'  => [ 'begin' => $begin , 'end' => $end ],
          	]
          );
        }
        
        return $this->render( 'sgr_calendarios/index.html.twig',[ 
                'form'  => $form->createView(),
                ]);
    }

    /**
        * @Route("/ajax/new/evento", name="sgr_calendarios_new", methods={"GET","POST"})
    */
    public function new(Request $request, Evento $evento)
    {
        $sgrEvento = new SgrEvento();
        $form = $this->createForm(SgrEventoType::class, $sgrEvento);
        $form->handleRequest($request);
        //exit;    
        if ($form->isSubmitted() && $form->isValid()) {
            
            //return new Response('hola');
            //dump($form);
            //exit;
            $entityManager = $this->getDoctrine()->getManager();
            
            //setUser 
            $sgrEvento->setUser($this->getUser());
            
            //setEstado
            $sgrEvento->setEstado('aprobado');
            
            //setUpdatedAt
            $sgrEvento->setUpdatedAt();

            //Si dias[] es vacio
            if(!$sgrEvento->getDias()) $sgrEvento->setDias([ $sgrEvento->getFInicio()->format('w') ]);

            $evento->setEvento($sgrEvento);
            $fechasEvento = new ArrayCollection($evento->calculateFechasEvento());
           
            $dias = $evento->calculateDias($fechasEvento);
            $sgrEvento->setDias($dias);
           
            $evento->setEvento($sgrEvento);
            //Si hay solapamiento, volvemos al formulario (con true flashea el error, si lo hay)
            if ($evento->solapa(true))
                return new Response('solapa');
                /*return $this->render('sgr_evento/new.html.twig', [
                        'sgr_evento' => $sgrEvento,
                        'form' => $form->createView(),
                ]);*/
            
            foreach ($fechasEvento as $dt) {
                $sgrFechasEvento = new sgrFechasEvento();
                $sgrFechasEvento->setFecha($dt);
                $entityManager->persist($sgrFechasEvento);
                $sgrEvento->addFecha($sgrFechasEvento);
            }

            $entityManager->persist($sgrEvento);
            $entityManager->flush();

            return $this->redirectToRoute('sgr_calendarios_index');
        }
        //dump('hola');
        //exit;
        
        return $this->render('sgr_calendarios/new.html.twig', [
            'sgr_evento' => $sgrEvento,
            'form' => $form->createView(),
        ]);
    }

}