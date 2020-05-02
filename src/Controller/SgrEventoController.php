<?php

namespace App\Controller;

use App\Entity\SgrEvento;
use App\Entity\SgrFechasEvento;
use App\Entity\SgrTitulacion;
use App\Entity\SgrAsignatura;
use App\Entity\SgrProfesor;
use App\Entity\SgrGrupoAsignatura;
//use App\Entity\SgrEspacio;

//use App\Service\Filters;
use App\Service\Evento;

use App\Form\SgrEventoType;
use App\Form\SgrFiltersSgrEventosType;


use App\Repository\SgrEventoRepository;
use App\Repository\SgrEspacioRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

// Include paginator interface
use Knp\Component\Pager\PaginatorInterface;


/**
 * @Route("/admin/sgr/evento")
 */
class SgrEventoController extends AbstractController
{
    
     private $session;

    /**
     * @Route("/index/{page}", name="sgr_evento_index", defaults={"page"=1}, methods={"GET","POST"})
     */
    public function index(Request $request, SgrEspacioRepository $sgrEspacioRepository, SgrEventoRepository $sgrEventoRepository, PaginatorInterface $paginator, SessionInterface $session, $page ): Response
    {

        //Session
        $this->session = $session;

        //form                
        $form = $this->createForm(SgrFiltersSgrEventosType::class);
        $form->handleRequest($request);
        $filtros = array();

        $current_month = (new \DateTime('now',new \DateTimeZone('Europe/Madrid')) )->format('m');
        $current_year =  (new \DateTime('now',new \DateTimeZone('Europe/Madrid')) )->format('Y');
        $current_month > 8 ?  $begin = new \DateTime('1-9-'.$current_year, new \DateTimeZone('Europe/Madrid')) : $begin = new \DateTime('1-9-'.($current_year-1), new \DateTimeZone('Europe/Madrid')); 
        $current_month > 8 ?  $end = new \DateTime('31-8-'.($current_year+1), new \DateTimeZone('Europe/Madrid')) : $end = new \DateTime('31-8-'.$current_year, new \DateTimeZone('Europe/Madrid'));

        
        $sgrEventos = $sgrEventoRepository->findAllbetween($begin, $end);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $page = 1;
            $data = $form->getData();
            
            $id_titulacion = null;
            if ($data['titulacion']){

                $filtros['Titulacion'] = $data['titulacion']->getNombre();
                $id_titulacion = $data['titulacion']->getId();
            }
            
            $curso = $data['curso'];
            
            $id_asignatura = null;
            if ($data['asignatura']){

                $filtros['Asignatura'] = $data['asignatura']->getNombre();
                $id_asignatura = $data['asignatura']->getId();
            }
            
            $id_profesor = null;
            if ($data['profesor']){

                $filtros['Profesor'] = $data['profesor']->getNombre();
                $id_profesor = $data['profesor']->getId();
            }

            $f_inicio = null;
            if ($data['f_inicio']){

                $f_inicio = date_create_from_format('d/m/Y', $data['f_inicio'], new \DateTimeZone('Europe/Madrid'));//$data['f_inicio'];////->getId();
            }
            
            $f_fin = null;
            if ($data['f_fin'])
                $f_fin = date_create_from_format('d/m/Y', $data['f_fin'], new \DateTimeZone('Europe/Madrid'));//$data['f_fin'];////$data['f_fin'];//->getId();
            
            //All espacios.
            $sgrEspacios = $sgrEspacioRepository->findAll();

            //filter by termino
            if($data['termino'])
            {
                $filtros['Categoria'] = $data['termino']->getNombre();    
                $sgrEspacios = $sgrEspacioRepository->findBy([ 'termino' => $data['termino'] ]);
            }
                
            //filter by espacio
            if( !$data['espacio']->isEmpty())
            {

                $espacios = $data['espacio'];
                $sgrEspacios = ( new ArrayCollection
                ($sgrEspacioRepository->findAll() ))->filter(function($sgrEspacio) use ($espacios) {
                                                                                return $espacios->contains($sgrEspacio);
                });
                    
                $listEspacios = array();
                foreach ($data['espacio'] as $espacio) {
                    $listEspacios[] = ucwords($espacio->getNombre());
                }
                $filtros['Espacios'] = implode(', ', $listEspacios);
            }

            $id_actividad = null;
            if ($data['actividad']){
                $filtros['Actividad'] = $data['actividad']->getActividad();
                $id_actividad = $data['actividad']->getId();
            } 
                
            $sgrEventos = $sgrEventoRepository->getSgrEventosByFilters( $id_titulacion, $curso, $id_asignatura, $id_profesor, $f_inicio, $f_fin, $sgrEspacios, $id_actividad);
        }

        
        if ( isset($data['ui']) )
        {
            //dump($data['ui']);
            //exit;
            $ui = json_decode($data['ui'],true);
            //dump($ui);
            
            if ( isset($ui['filters']) ){
                
                $ui['filters'] ? $showFilters = true : $showFilters = false;
                $this->session->set('showFilters', $ui['filters']);
            }
        }
        
        // the second argument is the value returned when the attribute doesn't exist
        $showFilters = $this->session->get('showFilters', [true]);
            

        $pagination = $paginator->paginate(
            $sgrEventos,
            $page,//$request->query->getInt('page', 1),
            10
        );


        //dump($pagination);
        //exit;
        return $this->render('sgr_evento/index.html.twig', [
            'pagination'    => $pagination,
            'form'          => $form->createView(),
            'filtros'       => $filtros,
            'data'          => [ 'begin' => $begin , 'end' => $end ],
            'view'          => 'anual', 
            'showFilters'   => $showFilters,
        ]);
    }

    /**
     * @Route("/new", name="sgr_evento_new", methods={"GET","POST"})
     */
    public function new(Request $request, Evento $evento): Response
    {
        $sgrEvento = new SgrEvento();
        //dump($sgrEvento);
        //exit;
        $form = $this->createForm(SgrEventoType::class, $sgrEvento);
        $form->handleRequest($request);
            
        if ($form->isSubmitted() && $form->isValid()) {
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
            
            if ( !empty($errors) )
                    return $this->render('sgr_evento/new.html.twig', [
                        'sgr_evento' => $sgrEvento,
                        'errors' => $errors,
                        'form' => $form->createView(),
                    ]);
            
            //Check solapamiento
            if ( !$evento->hasSolape()->isEmpty()  )
                return $this->render('sgr_evento/new.html.twig', [
                        'sgr_evento' => $sgrEvento,
                        'solapes' => $evento->hasSolape(),
                        'form' => $form->createView(),
                ]);
            
            //no errors, no salapamientos
            foreach ($evento->getAllFechas() as $dt) {
                $sgrFechasEvento = new sgrFechasEvento();
                $sgrFechasEvento->setFecha($dt);
                $entityManager->persist($sgrFechasEvento);
                $sgrEvento->addFecha($sgrFechasEvento);
            }

            $entityManager->persist($sgrEvento);
            $entityManager->flush();

            $this->addFlash(
                          'success',
                            'Evento salvado con éxito '
                        );
            return $this->redirectToRoute('sgr_evento_index');
        }

        return $this->render('sgr_evento/new.html.twig', [
            'sgr_evento' => $sgrEvento,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="sgr_evento_show", methods={"GET"})
     */
    public function show(SgrEvento $sgrEvento): Response
    {
        
        return $this->render('sgr_evento/show.html.twig', [
            'sgr_evento' => $sgrEvento,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="sgr_evento_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, SgrEvento $sgrEvento, Evento $evento): Response
    {
        $form = $this->createForm(SgrEventoType::class, $sgrEvento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $errors = array();
            $entityManager = $this->getDoctrine()->getManager();
            
            foreach ($sgrEvento->getFechas() as $fecha) {
                $sgrEvento->removeFecha($fecha);
            }
            
            $evento->setEvento($sgrEvento);
            //check valid selected dias
            if ( !$evento->isValidDias() )
                $errors[]['message'] = 'Selección de días no válida';
            
            if ( !empty($errors) )
                    return $this->render('sgr_evento/edit.html.twig', [
                        'sgr_evento' => $sgrEvento,
                        'errors' => $errors,
                        'form' => $form->createView(),
                    ]);    
            
            //No errors
            //dump($sgrEvento);
            //dump('cpntroller');
            //exit;
            //$evento->setEvento($sgrEvento);
            //Si solapamientos
            if ( !$evento->hasSolape()->isEmpty()  )
            
                return $this->render('sgr_evento/edit.html.twig', [
                        'sgr_evento' => $sgrEvento,
                        'solapes' => $evento->hasSolape(),
                        'form' => $form->createView(),
                ]);
        
            //no errors, no salapamientos
            foreach ($evento->getAllFechas() as $dt) {
                $sgrFechasEvento = new sgrFechasEvento();
                $sgrFechasEvento->setFecha($dt);
                $entityManager->persist($sgrFechasEvento);
                $sgrEvento->addFecha($sgrFechasEvento);
            }

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                          'success',
                            'Evento salvado con éxito '
                        );

            return $this->redirectToRoute('sgr_evento_index');
        }

        return $this->render('sgr_evento/edit.html.twig', [
            'sgr_evento' => $sgrEvento,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="sgr_evento_delete", methods={"DELETE"})
     */
    public function delete(Request $request, SgrEvento $sgrEvento): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sgrEvento->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($sgrEvento);
            $entityManager->flush();
        }

        return $this->redirectToRoute('sgr_evento_index');
    }

    /**
     * @Route("/ajax/getProfesores", methods={"GET"})
     */
    public function getProfesoresByAsignatura(Request $request)
    {
        if ($request->isXmlHttpRequest())
        {
            $profesores = new ArrayCollection();
            $grupos = new ArrayCollection();
            
            $request->query->has('sgr_evento') ? $data = 'sgr_evento' : $data = 'sgr_filters_sgr_eventos';

            $asignatura_id = $request->query->get($data)['asignatura'];
            $repositorySgrAsignatura = $this->getDoctrine()->getRepository(SgrAsignatura::class);
            $sgrAsignatura = $repositorySgrAsignatura->find($asignatura_id);
            
            if ($sgrAsignatura)
            {
                $grupos = $sgrAsignatura->getGrupos();
                if ($grupos)
                {
                    foreach ($grupos as $grupo)
                    {
                        $profesors = $grupo->getSgrProfesors();
                        if ($profesors)
                        {
                            foreach ($profesors as $profesor)
                            {
                                $profesores->add($profesor);
                            }    
                        }
                    }
                }
            }
            else
                $profesores = $this->getDoctrine()->getRepository(SgrProfesor::class)->findAll();
            
            $html['profesores'] = $this->render('sgr_form/optionsSelect.html.twig', [
                            'options' => $profesores,
                            'default' => ['value' => '', 'nombre' => 'Seleccione Profesor']
                        ]);    
            return $this->json($html);
        }   
        return new Response('');
    }

    /**
     * @Route("/ajax/getAsignaturas", methods={"GET"})
     */
    public function getAsignaturasByTitulacion(Request $request)
    {
        if ($request->isXmlHttpRequest())
        {
            $asignaturas = new ArrayCollection();
            $profesores = new ArrayCollection();
            $gruposAsignatura = new ArrayCollection();

            $request->query->has('sgr_evento') ? $data = 'sgr_evento' : $data = 'sgr_filters_sgr_eventos';

            $titulacion_id = $request->query->get($data)['titulacion'];
            $repositorySgrTitulacion = $this->getDoctrine()->getRepository(SgrTitulacion::class);
            
            $sgrTitulacion = $repositorySgrTitulacion->find($titulacion_id);
            
            if ($sgrTitulacion)
            {
                $asignaturas = $sgrTitulacion->getAsignaturas();
                if ($asignaturas)
                {
                    foreach ($asignaturas as $asignatura)
                    {
                        $grupos = $asignatura->getGrupos(); 
                        
                        if ($grupos)
                        {
                            foreach ($grupos as $grupo)
                            {
                                $gruposAsignatura->add($grupo);
                                $profesors = $grupo->getSgrProfesors();
                                if ($profesors)
                                {
                                    foreach ($profesors as $profesor)
                                    {
                                        $profesores->add($profesor);
                                    }
                                }
                            }
                        }
                    }
                }
            }
            else
            {
                $asignaturas = new ArrayCollection($this->getDoctrine()->getRepository(SgrAsignatura::class)->findAll());
                $profesores = new ArrayCollection($this->getDoctrine()->getRepository(SgrProfesor::class)->findAll());
                $grupoAsignatura = new ArrayCollection($this->getDoctrine()->getRepository(SgrGrupoAsignatura::class)->findAll());
            }
            
            
            if( array_key_exists('curso', $request->query->get($data)) && $request->query->get($data)['curso'] )
            {
                $curso = $request->query->get($data)['curso'];
                $asignaturas = $asignaturas->filter(function($asignatura) use ($curso){
                    return $asignatura->getCurso() ==  $curso;
                });
                if ($asignaturas)
                {
                    $profesores = new ArrayCollection();
                    foreach ($asignaturas as $asignatura)
                    {
                        $grupos = $asignatura->getGrupos(); 
                        if ($grupos)
                        {
                            foreach ($grupos as $grupo)
                            {
                                $gruposAsignatura->add($grupo);

                                $profesors = $grupo->getSgrProfesors();
                                if ($profesors)
                                {
                                    foreach ($profesors as $profesor)
                                    {
                                        $profesores->add($profesor);
                                    }
                                }
                            }
                        }
                    }
                }
            }


            $html['asignaturas'] = $this->render('sgr_form/optionsSelect.html.twig', [
                            'options' => $asignaturas,
                            'default' => ['value' => '', 'nombre' => 'Seleccione Asignatura']
                        ]);
            $html['profesores'] = $this->render('sgr_form/optionsSelect.html.twig', [
                            'options' => $profesores,
                            'default' => ['value' => '', 'nombre' => 'Seleccione Profesor']
                        ]);
            $html['grupos'] = $this->render('sgr_form/optionsSelect.html.twig', [
                            'options' => $gruposAsignatura,
                            'default' => ['value' => '', 'nombre' => 'Seleccione Grupo']
                        ]);
        
            return $this->json($html);
        }   
        
        return new Response('');
    }

}