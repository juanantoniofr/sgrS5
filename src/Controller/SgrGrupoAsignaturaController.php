<?php

namespace App\Controller;

use App\Entity\SgrGrupoAsignatura;
use App\Form\SgrGrupoAsignaturaType;
use App\Repository\SgrGrupoAsignaturaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sgr/grupo/asignatura")
 */
class SgrGrupoAsignaturaController extends AbstractController
{
    /**
     * @Route("/", name="sgr_grupo_asignatura_index", methods={"GET"})
     */
    public function index(SgrGrupoAsignaturaRepository $sgrGrupoAsignaturaRepository): Response
    {
        return $this->render('sgr_grupo_asignatura/index.html.twig', [
            'sgr_grupo_asignaturas' => $sgrGrupoAsignaturaRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="sgr_grupo_asignatura_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $sgrGrupoAsignatura = new SgrGrupoAsignatura();
        $form = $this->createForm(SgrGrupoAsignaturaType::class, $sgrGrupoAsignatura);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($sgrGrupoAsignatura);
            $entityManager->flush();

            return $this->redirectToRoute('sgr_grupo_asignatura_index');
        }

        return $this->render('sgr_grupo_asignatura/new.html.twig', [
            'sgr_grupo_asignatura' => $sgrGrupoAsignatura,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="sgr_grupo_asignatura_show", methods={"GET"})
     */
    public function show(SgrGrupoAsignatura $sgrGrupoAsignatura): Response
    {
        return $this->render('sgr_grupo_asignatura/show.html.twig', [
            'sgr_grupo_asignatura' => $sgrGrupoAsignatura,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="sgr_grupo_asignatura_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, SgrGrupoAsignatura $sgrGrupoAsignatura): Response
    {
        $form = $this->createForm(SgrGrupoAsignaturaType::class, $sgrGrupoAsignatura);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('sgr_grupo_asignatura_index');
        }

        return $this->render('sgr_grupo_asignatura/edit.html.twig', [
            'sgr_grupo_asignatura' => $sgrGrupoAsignatura,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="sgr_grupo_asignatura_delete", methods={"DELETE"})
     */
    public function delete(Request $request, SgrGrupoAsignatura $sgrGrupoAsignatura): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sgrGrupoAsignatura->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($sgrGrupoAsignatura);
            $entityManager->flush();
        }

        return $this->redirectToRoute('sgr_grupo_asignatura_index');
    }
}
