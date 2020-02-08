<?php

namespace App\Controller;

use App\Entity\SgrTermino;
use App\Form\SgrTerminoType;
use App\Repository\SgrTerminoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/sgr/termino")
 */
class SgrTerminoController extends AbstractController
{
    /**
     * @Route("/", name="sgr_termino_index", methods={"GET"})
     */
    public function index(SgrTerminoRepository $sgrTerminoRepository): Response
    {
        return $this->render('sgr_termino/index.html.twig', [
            'sgr_terminos' => $sgrTerminoRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="sgr_termino_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $sgrTermino = new SgrTermino();
        $form = $this->createForm(SgrTerminoType::class, $sgrTermino);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($sgrTermino);
            $entityManager->flush();

            return $this->redirectToRoute('sgr_termino_index');
        }

        return $this->render('sgr_termino/new.html.twig', [
            'sgr_termino' => $sgrTermino,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="sgr_termino_show", methods={"GET"})
     */
    public function show(SgrTermino $sgrTermino): Response
    {
        return $this->render('sgr_termino/show.html.twig', [
            'sgr_termino' => $sgrTermino,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="sgr_termino_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, SgrTermino $sgrTermino): Response
    {
        $form = $this->createForm(SgrTerminoType::class, $sgrTermino);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('sgr_termino_index');
        }

        return $this->render('sgr_termino/edit.html.twig', [
            'sgr_termino' => $sgrTermino,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="sgr_termino_delete", methods={"DELETE"})
     */
    public function delete(Request $request, SgrTermino $sgrTermino): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sgrTermino->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($sgrTermino);
            $entityManager->flush();
        }

        return $this->redirectToRoute('sgr_termino_index');
    }
}
