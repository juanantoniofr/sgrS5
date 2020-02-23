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
    public function new(Request $request): Response
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
            
            //set Fechas, Add fechas to sgrEvento, sgrFechasEvento persists
            $this->setSgrFechasEvento($sgrEvento, $entityManager);

            dump($sgrEvento);
            exit;
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
    public function edit(Request $request, SgrEvento $sgrEvento): Response
    {
        $form = $this->createForm(SgrEventoType::class, $sgrEvento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

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
    *
    */
    private function setSgrFechasEvento(SgrEvento $sgrEvento,$entityManager){

        $weekdays = ['0' => 'Monday', '1' => 'Tuesday', '2' => 'Wednesday', '3' => 'Thursday', '4' => 'Friday', '5' => 'Saturday'];
        
        if ( empty($sgrEvento->getDias()) ){
            $dias[] =  $sgrEvento->getFInicio()->format('w') - 1; // w=0 para domingo.
        }
        else {
            $dias[] = $sgrEvento->getDias();
        }
        
        
        $end = new \DateTime( $sgrEvento->getFFin()->format('Y-m-d') );
        $end->modify('+1 day'); //include last day in DatePeriod
        
        foreach ($dias as $dia) {

            $begin = new \DateTime( $sgrEvento->getFInicio()->format('Y-m-d') );
            $beginFromDayWeek = clone $begin->modify($weekdays[$dia].' this week');
            $interval = new \DateInterval('P7D');
            $period = new \DatePeriod($beginFromDayWeek, $interval, $end);
                     
            foreach ($period as $dt) {

                $sgrFechasEvento = new sgrFechasEvento();
                $sgrFechasEvento->setFecha($dt);
                $entityManager->persist($sgrFechasEvento);
                $sgrEvento->addFecha($sgrFechasEvento);
            }
        }
        return;
    }
}
