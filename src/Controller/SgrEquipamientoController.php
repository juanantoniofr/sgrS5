<?php

namespace App\Controller;

use App\Entity\SgrEquipamiento;
use App\Form\SgrEquipamientoType;
use App\Repository\SgrEquipamientoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/sgr/equipamiento")
 */
class SgrEquipamientoController extends AbstractController
{
    /**
     * @Route("/", name="sgr_equipamiento_index", methods={"GET"})
     */
    public function index(SgrEquipamientoRepository $sgrEquipamientoRepository): Response
    {
        return $this->render('sgr_equipamiento/index.html.twig', [
            'sgr_equipamientos' => $sgrEquipamientoRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="sgr_equipamiento_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $sgrEquipamiento = new SgrEquipamiento();
        $form = $this->createForm(SgrEquipamientoType::class, $sgrEquipamiento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($sgrEquipamiento);
            $entityManager->flush();

            return $this->redirectToRoute('sgr_equipamiento_index');
        }

        return $this->render('sgr_equipamiento/new.html.twig', [
            'sgr_equipamiento' => $sgrEquipamiento,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="sgr_equipamiento_show", methods={"GET"})
     */
    public function show(SgrEquipamiento $sgrEquipamiento): Response
    {
        return $this->render('sgr_equipamiento/show.html.twig', [
            'sgr_equipamiento' => $sgrEquipamiento,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="sgr_equipamiento_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, SgrEquipamiento $sgrEquipamiento): Response
    {
        $form = $this->createForm(SgrEquipamientoType::class, $sgrEquipamiento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('sgr_equipamiento_index');
        }

        return $this->render('sgr_equipamiento/edit.html.twig', [
            'sgr_equipamiento' => $sgrEquipamiento,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="sgr_equipamiento_delete", methods={"DELETE"})
     */
    public function delete(Request $request, SgrEquipamiento $sgrEquipamiento): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sgrEquipamiento->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($sgrEquipamiento);
            $entityManager->flush();
        }

        return $this->redirectToRoute('sgr_equipamiento_index');
    }
}
