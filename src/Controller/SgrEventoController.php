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
        $dias = [ 'Domingo', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
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
        * Persist sgrFechasEvento for all days from f_inicio to f_fin 
        * @param SgrEvento $sgrEvento
        * @param $entityManager
        *
        * @return void 
    */
    private function setSgrFechasEvento(SgrEvento $sgrEvento,$entityManager){

        if ( empty($sgrEvento->getDias()) ){
            $weekDays[] =  $sgrEvento->getFInicio()->format('l'); // Monday,....
        }
        else {
            $weekDays = $sgrEvento->getDias();
        }
        
        $days = [ 'Sunday', 'Monday', 'tuesday', 'wednesday', 'thursday', 'saturday'];
        $end = new \DateTime( $sgrEvento->getFFin()->format('Y-m-d') );
        $end->modify('+1 day'); //include last day in DatePeriod
        $begin = new \DateTime( $sgrEvento->getFInicio()->format('Y-m-d') );
        $interval = new \DateInterval('P7D');
        $period = new \DatePeriod($begin, $interval, $end);
        foreach ($period as $dt) {

            foreach ($weekDays as $day) {

                $dtdw = clone $dt->modify($days[$day]. ' this week');
                if ( (int) ($dtdw->diff($end)->format('%d')) > 0 ){                
                    $sgrFechasEvento = new sgrFechasEvento();
                    $sgrFechasEvento->setFecha($dtdw);
                    $entityManager->persist($sgrFechasEvento);
                    $sgrEvento->addFecha($sgrFechasEvento);
                }
            }
        }
        return;
    }
}
