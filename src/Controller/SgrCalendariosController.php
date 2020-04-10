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
       * @Route("/vista/{view}", name="sgr_calendarios_vista", methods={"GET","POST"}), defaults={"view": "anual"}
    */
    public function index(Request $request,SgrEspacioRepository $sgrEspacioRepository, sgrFechasEventoRepository $sgrFechasEventoRepository, sgrTerminoRepository $sgrTerminoRepository, String $view)
    {
        
        $form = $this->createForm(SgrFiltersSgrEventosType::class);
        $form->handleRequest($request);
        $sgrEspacios = $sgrEspacioRepository->findAll();
        $aCalendarios = array();
        $filtros = array();
        //if (!$form->isSubmitted())
        //{
            $current_month = (new \DateTime('now',new \DateTimeZone('Europe/Madrid')) )->format('m');
            $current_year =  (new \DateTime('now',new \DateTimeZone('Europe/Madrid')) )->format('Y');
            
            switch ($view) {
                case 'diaria':
                    $begin = new \DateTime('now',new \DateTimeZone('Europe/Madrid'));//hoy
                    $end = new \DateTime('now',new \DateTimeZone('Europe/Madrid'));
                    $template = 'sgr_calendarios/viewDay.html.twig';
                    break;
                case 'semanal':
                    $begin = new \DateTime('Monday this week', new \DateTimeZone('Europe/Madrid')); 
                    $end = new \DateTime('Sunday this week', new \DateTimeZone('Europe/Madrid'));
                    $template = 'sgr_calendarios/viewWeek.html.twig';
                    break;
                case 'mensual':
                    $begin = new \DateTime('Monday this week', new \DateTimeZone('Europe/Madrid')); 
                    $end = new \DateTime('Sunday this week', new \DateTimeZone('Europe/Madrid'));
                    $template = 'sgr_calendarios/viewMonth.html.twig';
                    break;    
                default: //case 'anual':
                    $current_month > 8 ?  $begin = new \DateTime('1-9-'.$current_year, new \DateTimeZone('Europe/Madrid')) : $begin = new \DateTime('1-9-'.($current_year-1), new \DateTimeZone('Europe/Madrid')); 
                    $current_month > 8 ?  $end = new \DateTime('31-8-'.($current_year+1), new \DateTimeZone('Europe/Madrid')) : $end = new \DateTime('31-8-'.$current_year, new \DateTimeZone('Europe/Madrid'));
                    $template = 'sgr_calendarios/viewWeek.html.twig';
                    break;
            }
            
            $sgrFechasEvento = $sgrFechasEventoRepository->findBetween($begin, $end);
            $aCalendarios = $this->getCalendarios($sgrEspacios, $sgrFechasEvento);
        //}

        if ($form->isSubmitted())
        {

            if ($form->isValid()) 
            {
                
                $data = $form->getData();
                if ($data['f_inicio'])
                    $begin = date_create_from_format('d/m/Y H:i', $data['f_inicio'] . "00:00", new \DateTimeZone('Europe/Madrid'));
                switch ($view) {
                    case 'diaria':
                        $template = 'sgr_calendarios/viewDay.html.twig';
                        $data['f_fin'] ? $end = date_create_from_format('d/m/Y H:i', $data['f_fin'] . "00:00", new \DateTimeZone('Europe/Madrid')) :  $end = date_create_from_format('d/m/Y H:i', $data['f_inicio'] . "00:00", new \DateTimeZone('Europe/Madrid')); 
                        break;
                    case 'semanal':
                        $template = 'sgr_calendarios/viewWeek.html.twig';
                        $data['f_fin'] ? $end = date_create_from_format('d/m/Y H:i', $data['f_fin'] . "00:00", new \DateTimeZone('Europe/Madrid')) :  $end = clone $begin->modify('Sunday this week');
                        break;
                    case 'mensual':
                        $template = 'sgr_calendarios/viewMonth.html.twig';
                        $data['f_fin'] ? $end = date_create_from_format('d/m/Y H:i', $data['f_fin'] . "00:00", new \DateTimeZone('Europe/Madrid')) :  $end = clone $begin->modify('Sunday this week');
                        break;
                    default: //case 'anual':
                        $template = 'sgr_calendarios/viewWeek.html.twig';
                        break;
                }
                
                //filter by fechas
                $sgrFechasEvento = $sgrFechasEventoRepository->findBetween($begin, $end);
                
                //filter by actividad
                if( $data['actividad'])
                {
                    $filtros['Actividad'] = $data['actividad']->getActividad();
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
                    $filtros['Titulacion'] = $data['titulacion']->getNombre();
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
                    $filtros['Asignatura'] = $data['asignatura']->getNombre();
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
                    $filtros['Profesor'] = $data['profesor']->getNombre();
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
                if($data['termino'])
                    $sgrEspacios = $sgrEspacioRepository->findBy([ 'termino' => $data['termino'] ]);
                
                //filter by espacio
                if( !$data['espacio']->isEmpty())
                {
                    $espacios = $data['espacio'];
                    $sgrEspacios = ( new ArrayCollection
                      ($sgrEspacioRepository->findAll() ))->filter(function($sgrEspacio) use ($espacios) {
                        return $espacios->contains($sgrEspacio);});//(['nombre' => $data['espacio'] ]);
                }
            }
            else
            {
                //******
                // No valid form
                //********
                //dump($view);
                //dump($form);//->isSubmitted());
                dump($form->getData());//['f_inicio']);
                dump($form->getErrors(true));
                foreach ($form->getErrors(true) as $error) {
                    dump($error->getMessage());
                }
                exit;
            }
        }

        
        $aCalendarios = $this->getCalendarios($sgrEspacios, $sgrFechasEvento);
        return $this->render( $template,[ 
            'form'  => $form->createView(),
            'aCalendarios' => $aCalendarios,
            'data'  => [ 'begin' => $begin , 'end' => $end , 'filtros' => $filtros],
            'view' => $view,
            'month' => $current_month, 
            'year' => $current_year,    
            //'numDaysView' => (int) $begin->diff($end)->format('%d'),
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
        

        
        if ( $form->isSubmitted() )
        {
            if( !$form->isValid() )
            {
                //return $this->json( dump($form->getErrors(true)) );
                //return $this->json( dump($form->getData()) );
                $html = $this->render('sgr_form/_errors.html.twig', [
                            'errors' => $form->getErrors(true),
                        ]);
                return $this->json($html);
            }
            else
            {

                $errors = array();
                $entityManager = $this->getDoctrine()->getManager();
            
                //setUser 
                $sgrEvento->setUser($this->getUser());
                
                //setEstado
                $sgrEvento->setEstado('aprobado');
            
                //setUpdatedAt
                $sgrEvento->setUpdatedAt();
                
                $evento->setEvento($sgrEvento);

                //check valid selected dias
                if ( !$evento->isValidDias() )
                    $errors[]['message'] = 'Selección de días no válida';
            
                //si errors return
                if( !empty($errors) )
                {
                    
                    $html = $this->render('sgr_form/_errors.html.twig', [
                            'errors' => $errors,
                    ]);
                    return $this->json($html);
                }
                
                //No errors
                $evento->setEvento($sgrEvento);
                //Si solapamiento
                if ( !$evento->hasSolape()->isEmpty() )
                {
                    //return $this->json('solape');
                    //return $this->json( 'hola' );
                    $html =  $this->render('sgr_form/_errors.html.twig', [
                                    'solapes' => $evento->hasSolape(),
                    ]);
                    return $this->json($html);
                }
                
                //NO errors, no solapamientos
                foreach ($evento->getAllFechas() as $dt) {
                    //return $this->json('no solape');
                    $sgrFechasEvento = new sgrFechasEvento();
                    $sgrFechasEvento->setFecha($dt);
                    $entityManager->persist($sgrFechasEvento);
                    $sgrEvento->addFecha($sgrFechasEvento);
                }

                $entityManager->persist($sgrEvento);
                $entityManager->flush();
                //return new Response('éxito'); //$this->redirectToRoute('sgr_calendarios_index');
                return $this->json(true);
                //Flash resultado 
                //exit;
            }
        }
        
        
        return $this->render('sgr_calendarios/new.html.twig', [
                'sgr_evento' => $sgrEvento,
                'form' => $form->createView(),
                ]);
        //return new Response('');
    }


    public function getCalendarios($sgrEspacios, $sgrFechasEvento){

        $aCalendarios = array();

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

        return $aCalendarios;
    }
}