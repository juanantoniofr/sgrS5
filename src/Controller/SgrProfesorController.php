<?php

namespace App\Controller;

use App\Entity\SgrProfesor;
use App\Form\SgrProfesorType;
use App\Repository\SgrProfesorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/sgr/profesor")
 */
class SgrProfesorController extends AbstractController
{
    /**
     * @Route("/", name="sgr_profesor_index", methods={"GET"})
     */
    public function index(SgrProfesorRepository $sgrProfesorRepository): Response
    {
        return $this->render('sgr_profesor/index.html.twig', [
            'sgr_profesors' => $sgrProfesorRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="sgr_profesor_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $sgrProfesor = new SgrProfesor();
        $form = $this->createForm(SgrProfesorType::class, $sgrProfesor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($sgrProfesor);
            $entityManager->flush();

            return $this->redirectToRoute('sgr_profesor_index');
        }

        return $this->render('sgr_profesor/new.html.twig', [
            'sgr_profesor' => $sgrProfesor,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="sgr_profesor_show", methods={"GET"})
     */
    public function show(SgrProfesor $sgrProfesor): Response
    {
        return $this->render('sgr_profesor/show.html.twig', [
            'sgr_profesor' => $sgrProfesor,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="sgr_profesor_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, SgrProfesor $sgrProfesor): Response
    {
        $form = $this->createForm(SgrProfesorType::class, $sgrProfesor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('sgr_profesor_index');
        }

        return $this->render('sgr_profesor/edit.html.twig', [
            'sgr_profesor' => $sgrProfesor,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="sgr_profesor_delete", methods={"DELETE"})
     */
    public function delete(Request $request, SgrProfesor $sgrProfesor): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sgrProfesor->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($sgrProfesor);
            $entityManager->flush();
        }

        return $this->redirectToRoute('sgr_profesor_index');
    }
}
