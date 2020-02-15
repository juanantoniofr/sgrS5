<?php

namespace App\Controller;

use App\Entity\SgrTitulacion;
use App\Form\SgrTitulacionType;
use App\Repository\SgrTitulacionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/sgr/titulacion")
 */
class SgrTitulacionController extends AbstractController
{
    /**
     * @Route("/", name="sgr_titulacion_index", methods={"GET"})
     */
    public function index(SgrTitulacionRepository $sgrTitulacionRepository): Response
    {
        return $this->render('sgr_titulacion/index.html.twig', [
            'sgr_titulacions' => $sgrTitulacionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="sgr_titulacion_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $sgrTitulacion = new SgrTitulacion();
        $form = $this->createForm(SgrTitulacionType::class, $sgrTitulacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($sgrTitulacion);
            $entityManager->flush();

            return $this->redirectToRoute('sgr_titulacion_index');
        }

        return $this->render('sgr_titulacion/new.html.twig', [
            'sgr_titulacion' => $sgrTitulacion,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="sgr_titulacion_show", methods={"GET"})
     */
    public function show(SgrTitulacion $sgrTitulacion): Response
    {
        return $this->render('sgr_titulacion/show.html.twig', [
            'sgr_titulacion' => $sgrTitulacion,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="sgr_titulacion_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, SgrTitulacion $sgrTitulacion): Response
    {
        $form = $this->createForm(SgrTitulacionType::class, $sgrTitulacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('sgr_titulacion_index');
        }

        return $this->render('sgr_titulacion/edit.html.twig', [
            'sgr_titulacion' => $sgrTitulacion,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="sgr_titulacion_delete", methods={"DELETE"})
     */
    public function delete(Request $request, SgrTitulacion $sgrTitulacion): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sgrTitulacion->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($sgrTitulacion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('sgr_titulacion_index');
    }
}
