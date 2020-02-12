<?php

namespace App\Controller;

use App\Entity\SgrEspacio;
use App\Form\SgrEspacioType;
use App\Repository\SgrEspacioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/sgr/espacio")
 */
class SgrEspacioController extends AbstractController
{
    /**
     * @Route("/", name="sgr_espacio_index", methods={"GET"})
     */
    public function index(SgrEspacioRepository $sgrEspacioRepository): Response
    {
        return $this->render('sgr_espacio/index.html.twig', [
            'sgr_espacios' => $sgrEspacioRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="sgr_espacio_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $sgrEspacio = new SgrEspacio();
        $form = $this->createForm(SgrEspacioType::class, $sgrEspacio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($sgrEspacio);
            $entityManager->flush();

            return $this->redirectToRoute('sgr_espacio_index');
        }

        return $this->render('sgr_espacio/new.html.twig', [
            'sgr_espacio' => $sgrEspacio,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="sgr_espacio_show", methods={"GET"})
     */
    public function show(SgrEspacio $sgrEspacio): Response
    {
        return $this->render('sgr_espacio/show.html.twig', [
            'sgr_espacio' => $sgrEspacio,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="sgr_espacio_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, SgrEspacio $sgrEspacio): Response
    {
        $form = $this->createForm(SgrEspacioType::class, $sgrEspacio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('sgr_espacio_index');
        }

        return $this->render('sgr_espacio/edit.html.twig', [
            'sgr_espacio' => $sgrEspacio,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="sgr_espacio_delete", methods={"DELETE"})
     */
    public function delete(Request $request, SgrEspacio $sgrEspacio): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sgrEspacio->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($sgrEspacio);
            $entityManager->flush();
        }

        return $this->redirectToRoute('sgr_espacio_index');
    }
}