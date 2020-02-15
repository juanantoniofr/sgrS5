<?php

namespace App\Controller;

use App\Entity\SgrAsignatura;
use App\Form\SgrAsignaturaType;
use App\Repository\SgrAsignaturaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/sgr/asignatura")
 */
class SgrAsignaturaController extends AbstractController
{
    /**
     * @Route("/", name="sgr_asignatura_index", methods={"GET"})
     */
    public function index(SgrAsignaturaRepository $sgrAsignaturaRepository): Response
    {
        return $this->render('sgr_asignatura/index.html.twig', [
            'sgr_asignaturas' => $sgrAsignaturaRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="sgr_asignatura_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $sgrAsignatura = new SgrAsignatura();
        $form = $this->createForm(SgrAsignaturaType::class, $sgrAsignatura);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($sgrAsignatura);
            $entityManager->flush();

            return $this->redirectToRoute('sgr_asignatura_index');
        }

        return $this->render('sgr_asignatura/new.html.twig', [
            'sgr_asignatura' => $sgrAsignatura,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="sgr_asignatura_show", methods={"GET"})
     */
    public function show(SgrAsignatura $sgrAsignatura): Response
    {
        return $this->render('sgr_asignatura/show.html.twig', [
            'sgr_asignatura' => $sgrAsignatura,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="sgr_asignatura_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, SgrAsignatura $sgrAsignatura): Response
    {
        $form = $this->createForm(SgrAsignaturaType::class, $sgrAsignatura);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('sgr_asignatura_index');
        }

        return $this->render('sgr_asignatura/edit.html.twig', [
            'sgr_asignatura' => $sgrAsignatura,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="sgr_asignatura_delete", methods={"DELETE"})
     */
    public function delete(Request $request, SgrAsignatura $sgrAsignatura): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sgrAsignatura->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($sgrAsignatura);
            $entityManager->flush();
        }

        return $this->redirectToRoute('sgr_asignatura_index');
    }
}
