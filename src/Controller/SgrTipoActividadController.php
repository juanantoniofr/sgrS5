<?php

namespace App\Controller;

use App\Entity\SgrTipoActividad;
use App\Form\SgrTipoActividadType;
use App\Repository\SgrTipoActividadRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// Include paginator interface
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/admin/sgr/tipo/actividades")
 */
class SgrTipoActividadController extends AbstractController
{
    /**
     * @Route("/index/{page}", name="sgr_tipo_actividad_index", defaults={"page"=1}, methods={"GET"})
     */
    public function index(SgrTipoActividadRepository $sgrTipoActividadRepository, PaginatorInterface $paginator, $page): Response
    {

        //$actividades = $sgrTipoActividadRepository->findAll();
        $actividades = $sgrTipoActividadRepository->findBy([],['actividad' => 'ASC']);
        //dump($actividades);
        //exit;
        $pagination = $paginator->paginate(
            $actividades,
            $page,//$request->query->getInt('page', 1),
            10
        );
        
        return $this->render('sgr_tipo_actividad/index.html.twig', [
            'pagination'    => $pagination,
            //'sgr_tipo_actividads' => $sgrTipoActividadRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="sgr_tipo_actividad_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $sgrTipoActividad = new SgrTipoActividad();
        $form = $this->createForm(SgrTipoActividadType::class, $sgrTipoActividad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($sgrTipoActividad);
            $entityManager->flush();

            return $this->redirectToRoute('sgr_tipo_actividad_index');
        }

        return $this->render('sgr_tipo_actividad/new.html.twig', [
            'sgr_tipo_actividad' => $sgrTipoActividad,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="sgr_tipo_actividad_show", methods={"GET"})
     */
    public function show(SgrTipoActividad $sgrTipoActividad): Response
    {
        return $this->render('sgr_tipo_actividad/show.html.twig', [
            'sgr_tipo_actividad' => $sgrTipoActividad,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="sgr_tipo_actividad_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, SgrTipoActividad $sgrTipoActividad): Response
    {
        $form = $this->createForm(SgrTipoActividadType::class, $sgrTipoActividad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('sgr_tipo_actividad_index');
        }

        return $this->render('sgr_tipo_actividad/edit.html.twig', [
            'sgr_tipo_actividad' => $sgrTipoActividad,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="sgr_tipo_actividad_delete", methods={"DELETE"})
     */
    public function delete(Request $request, SgrTipoActividad $sgrTipoActividad): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sgrTipoActividad->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($sgrTipoActividad);
            $entityManager->flush();
        }

        return $this->redirectToRoute('sgr_tipo_actividad_index');
    }
}
