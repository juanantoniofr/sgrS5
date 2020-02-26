<?php

namespace App\Controller;

use App\Entity\SgrEvento;
use App\Entity\SgrFechasEvento;
use App\Form\SgrEventoType;
use App\Repository\SgrEventoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\EventoUtils;


/**
 * @Route("/admin/sgr/evento")
 */
class SgrEventoController extends AbstractController
{
    /**
     * @Route("/", name="sgr_evento_index", methods={"GET"})
     */
    public function index(SgrEventoRepository $sgrEventoRepository): Response
    {
        
        return $this->render('sgr_evento/index.html.twig', [
            'sgr_eventos' => $sgrEventoRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="sgr_evento_new", methods={"GET","POST"})
     */
    public function new(Request $request, EventoUtils $eventoUtils): Response
    {
        $sgrEvento = new SgrEvento();
        $form = $this->createForm(SgrEventoType::class, $sgrEvento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            
            //user 
            $sgrEvento->setUser($this->getUser());
            
            //estado
            $sgrEvento->setEstado('aprobado');
            
            //updatedAt
            $sgrEvento->setUpdatedAt();
            
            //dump($evento->setEvento($sgrEvento));
            //$evento = $evento->setEvento($sgrEvento);
            //dump($evento->solapa());
            //exit;
            
            if ($eventoUtils->setEvento($sgrEvento)->solapa())
            
                return $this->render('sgr_evento/new.html.twig', [
                        'sgr_evento' => $sgrEvento,
                        'form' => $form->createView(),
                ]);
            
            $this->addDates($sgrEvento,$entityManager,$eventoUtils);
            
            //dump($sgrEvento);
            //exit;
            $entityManager->persist($sgrEvento);
            $entityManager->flush();

            $this->addFlash(
                          'success',
                            'Espacio libre el día ' . $date->format('d-m-Y')
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
        //Data transformer
        $dias = [ 'Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
        foreach ($sgrEvento->getDias() as $i) {
            $d_es[] = $dias[$i];
        }
        
        $sgrEvento->setDias($d_es);
        return $this->render('sgr_evento/show.html.twig', [
            'sgr_evento' => $sgrEvento,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="sgr_evento_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, SgrEvento $sgrEvento, EventoUtils $eventoUtils): Response
    {
        $form = $this->createForm(SgrEventoType::class, $sgrEvento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //dump($sgrEvento->getDias());
            //dump($form->getData());
            
            $entityManager = $this->getDoctrine()->getManager();
            
            foreach ($sgrEvento->getFechas() as $fecha) {
                $sgrEvento->removeFecha($fecha);
            }
            
            if ($eventoUtils->setEvento($sgrEvento)->solapa())
            
                return $this->render('sgr_evento/new.html.twig', [
                        'sgr_evento' => $sgrEvento,
                        'form' => $form->createView(),
                ]);
            
            $this->addDates($sgrEvento,$entityManager,$eventoUtils);
            
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

  
    public function addDates(SgrEvento $sgrEvento, $entityManager, EventoUtils $eventoUtils){

        //Array datetime fechasEventos from f_inicio to f_fin 
        $dateTimeFechasEvento = $eventoUtils->calculateDates();

        foreach ($dateTimeFechasEvento as $dtFechaEvento) {
                $sgrFechasEvento = new sgrFechasEvento();
                $sgrFechasEvento->setFecha($dtFechaEvento);
                $entityManager->persist($sgrFechasEvento);
                $sgrEvento->addFecha($sgrFechasEvento);
            }
    }
}