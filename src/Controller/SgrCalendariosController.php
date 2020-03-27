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
        
        //for modal new SgrEvento
        $sgrEvento = new SgrEvento();
        $formNewSgrEvento = $this->createForm(SgrEventoType::class, $sgrEvento);
        $formNewSgrEvento->handleRequest($request);

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
                'formNewSgrEvento' => $formNewSgrEvento->createView(),
                'data'  => [ 'begin' => $begin , 'end' => $end ],
          	]
          );
        }
        
        return $this->render( 'sgr_calendarios/index.html.twig',[ 
                'form'  => $form->createView(),
                'formNewSgrEvento' => $formNewSgrEvento->createView(),
            ]);
    }

    /**
        * @Route("/ajax/new/evento", methods={"GET","POST"})
    */
    public function new(Request $request, Evento $evento, SgrEspacioRepository $sgrEspacioRepository)
    {

        $data = $request->query->get('sgr_evento');
        /*if (!$data->isValid()){

            dump($data);

        }*/
        $sgrEvento = new SgrEvento();
        $form = $this->createForm(SgrEventoType::class, $sgrEvento);
        $form->handleRequest($request);
        dump($form);
        dump($form->isValid());
        exit;

        $data = $request->query->get('sgr_evento');

        $respuesta['message'] = 'Error';
        $data = new ArrayCollection($data);
        $sgrEvento = new SgrEvento;
        $entityManager = $this->getDoctrine()->getManager();

        //dump($data);
        //exit;
        //set espacio
        $sgrEvento->setEspacio( $sgrEspacioRepository->find($data->get('espacio')) );
        //setUser 
        $sgrEvento->setUser($this->getUser());
            
        //setEstado
        $sgrEvento->setEstado('aprobado');
            
        //setUpdatedAt
        $sgrEvento->setUpdatedAt();

        //SetFInicio
        $sgrEvento->setFInicio( date_create_from_format('d/m/Y H:i', $data->get('f_inicio') . '00:00', new \DateTimeZone('Europe/Madrid')) );
        //setFFin
        $sgrEvento->setFFin( date_create_from_format('d/m/Y H:i', $data->get('f_fin') . '00:00', new \DateTimeZone('Europe/Madrid')) );
        //SetHInicio
        $sgrEvento->setHInicio( date_create_from_format('Y/m/d H:i', '1970/1/1 ' . $data->get('h_inicio'), new \DateTimeZone('Europe/Madrid')) );
        //setFFin
        $sgrEvento->setHFin( date_create_from_format('Y/m/d H:i', '1970/1/1 ' . $data->get('h_fin'), new \DateTimeZone('Europe/Madrid')) );
        //Si $data['dias'] es vacio
        $data->get('dias') ? $sgrEvento->setDias( $data->get('dias') ) : $sgrEvento->setDias([ $SgrEvento->getFInicio()->format('w') ]);
        //set fechasEvento
        $evento->setEvento($sgrEvento);
        $fechasEvento = new ArrayCollection($evento->calculateFechasEvento());
        $fechasEvento->forAll(function($key, $fechaEvento) use(&$sgrEvento, $entityManager) {
            $sgrFechasEvento = new sgrFechasEvento();
            $sgrFechasEvento->setFecha($fechaEvento);
            $entityManager->persist($sgrFechasEvento);
            $sgrEvento->addFecha($sgrFechasEvento);
            return true;
        });
        //set dias
        $dias = $evento->calculateDias($fechasEvento);
        $sgrEvento->setDias($dias);
        
        $evento->setEvento($sgrEvento);
        //dump($evento);
        //dump($sgrEvento);
        //dump($fechasEvento);   
        //dump($evento->solapa(false));
        if ($evento->solapa(false)){
            $respuesta['result'] = 'error';
            $respuesta['message'] = 'El evento no se pudo guardar. Existe solapamientos...';
            return $this->json($respuesta);
        }


        $entityManager->persist($sgrEvento);
        $entityManager->flush();
        
        $respuesta['result'] = 'success';
        $respuesta['message'] = 'Evento Salvado con éxito...';
        return $this->json($respuesta);
    }

}