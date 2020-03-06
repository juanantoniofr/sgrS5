<?php

namespace App\Controller;

use App\Entity\SgrEvento;
use App\Entity\SgrFechasEvento;
use App\Entity\SgrTitulacion;
use App\Entity\SgrAsignatura;
use App\Entity\SgrProfesor;
use App\Entity\SgrGrupoAsignatura;

//use App\Service\Filters;
use App\Service\Evento;

use App\Form\SgrEventoType;
use App\Form\SgrFiltersSgrEventosType;


use App\Repository\SgrEventoRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


/**
 * @Route("/admin/sgr/evento")
 */
class SgrEventoController extends AbstractController
{
    
    /**
     * @Route("/ajax-getAsignaturas", methods={"GET"})
     */
    public function getAsignaturasByTitulacion(Request $request)
    {
        if ($request->isXmlHttpRequest())
        {
            $asignaturas = array();
            $profesores = new ArrayCollection();

            $titulacion_id = $request->query->get('sgr_filters_sgr_eventos')['titulacion'];
            
            $repositorySgrTitulacion = $this->getDoctrine()->getRepository(SgrTitulacion::class);

            $sgrTitulacion = $repositorySgrTitulacion->find($titulacion_id);
            
            if ($sgrTitulacion){
                $asignaturas = $sgrTitulacion->getAsignaturas();
                if ($asignaturas){
                    foreach ($asignaturas as $asignatura) {
                        //dump($asignatura);
                        //dump($asignatura->getGrupos()->count());
                        foreach ($asignatura->getGrupos() as $grupo) {
                            //dump($grupo->getNombre());
                            $profesors = $grupo->getSgrProfesors();
                            foreach ($profesors as $profesor) {
                                $profesores->add($profesor);
                            }
                        }

                    }
                }
            }
            else {
                $asignaturas = $this->getDoctrine()->getRepository(SgrAsignatura::class)->findAll();
                $profesores = $this->getDoctrine()->getRepository(SgrProfesor::class)->findAll();
            }
            //dump($profesores);
            //dump($asignaturas);
            //exit;
            $html['asignaturas'] = $this->render('sgr_form/optionsSelect.html.twig', [
                            'options' => $asignaturas,
                            'default' => ['value' => '', 'nombre' => 'Seleccione Asignatura']
                        ]);
            $html['profesores'] = $this->render('sgr_form/optionsSelect.html.twig', [
                            'options' => $profesores,
                            'default' => ['value' => '', 'nombre' => 'Seleccione Profesor']
                        ]);    
            //dump($html);
            //exit;
            //return new Response($html);
            return $this->json($html);
        }   
        return new Response('');
    }


    /**
     * @Route("/", name="sgr_evento_index", methods={"GET"})
     */
    public function index(SgrEventoRepository $sgrEventoRepository): Response
    {
                        
        $form = $this->createForm(SgrFiltersSgrEventosType::class);

        return $this->render('sgr_evento/index.html.twig', [
            'sgr_eventos'   => $sgrEventoRepository->findAll(),
            'form'          => $form->createView(),
        ]);
    }

    /**
     * @Route("/new", name="sgr_evento_new", methods={"GET","POST"})
     */
    public function new(Request $request, Evento $evento): Response
    {
        $sgrEvento = new SgrEvento();
        $form = $this->createForm(SgrEventoType::class, $sgrEvento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            
            //setUser 
            $sgrEvento->setUser($this->getUser());
            
            //setEstado
            $sgrEvento->setEstado('aprobado');
            
            //setUpdatedAt
            $sgrEvento->setUpdatedAt();

            //setFfin para eventos no periódicos (sin repetición)
            if(!$sgrEvento->getFFin()) $sgrEvento->setFFin($sgrEvento->getFInicio());
            
            //setDias para eventos no periódicos (sin repetición)
            if(!$sgrEvento->getDias()) $sgrEvento->setDias([ $sgrEvento->getFInicio()->format('w') ]);

            $evento->setEvento($form->getData());
            $fechasEvento = $evento->calculateFechasEvento();
            //Si hay solapamiento, volvemos al formulario
            if ($evento->solapa())
            
                return $this->render('sgr_evento/new.html.twig', [
                        'sgr_evento' => $sgrEvento,
                        'form' => $form->createView(),
                ]);
            
            foreach ($fechasEvento as $dt) {
                $sgrFechasEvento = new sgrFechasEvento();
                $sgrFechasEvento->setFecha($dt);
                $entityManager->persist($sgrFechasEvento);
                $sgrEvento->addFecha($sgrFechasEvento);
            }

            $entityManager->persist($sgrEvento);
            $entityManager->flush();

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
            
            $entityManager = $this->getDoctrine()->getManager();
            
            foreach ($sgrEvento->getFechas() as $fecha) {
                $sgrEvento->removeFecha($fecha);
            }
            
            //setFfin y setDias para eventos no periódicos (sin repetición)
            if($sgrEvento->getFFin() 
                != $sgrEvento->getFInicio()) {
            
                $sgrEvento->setFFin($sgrEvento->getFInicio());
                $sgrEvento->setDias([ $sgrEvento->getFInicio()->format('w') ]);
            }

            $evento->setEvento($form->getData());
            $fechasEvento = $evento->calculateFechasEvento();
            //Si hay solapamiento, volvemos al formulario
            if ($evento->solapa())
            
                return $this->render('sgr_evento/new.html.twig', [
                        'sgr_evento' => $sgrEvento,
                        'form' => $form->createView(),
                ]);
        
            foreach ($fechasEvento as $dt) {
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

}