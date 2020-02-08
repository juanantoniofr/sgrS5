<?php

namespace App\Controller;

use App\Entity\SgrTaxonomia;
use App\Form\SgrTaxonomiaType;
use App\Repository\SgrTaxonomiaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/sgr/taxonomia")
 */
class SgrTaxonomiaController extends AbstractController
{
    /**
     * @Route("/", name="sgr_taxonomia_index", methods={"GET"})
     */
    public function index(SgrTaxonomiaRepository $sgrTaxonomiaRepository): Response
    {
        return $this->render('sgr_taxonomia/index.html.twig', [
            'sgr_taxonomias' => $sgrTaxonomiaRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="sgr_taxonomia_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $sgrTaxonomium = new SgrTaxonomia();
        $form = $this->createForm(SgrTaxonomiaType::class, $sgrTaxonomium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($sgrTaxonomium);
            $entityManager->flush();

            return $this->redirectToRoute('sgr_taxonomia_index');
        }

        return $this->render('sgr_taxonomia/new.html.twig', [
            'sgr_taxonomium' => $sgrTaxonomium,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="sgr_taxonomia_show", methods={"GET"})
     */
    public function show(SgrTaxonomia $sgrTaxonomium): Response
    {
        return $this->render('sgr_taxonomia/show.html.twig', [
            'sgr_taxonomium' => $sgrTaxonomium,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="sgr_taxonomia_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, SgrTaxonomia $sgrTaxonomium): Response
    {
        $form = $this->createForm(SgrTaxonomiaType::class, $sgrTaxonomium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('sgr_taxonomia_index');
        }

        return $this->render('sgr_taxonomia/edit.html.twig', [
            'sgr_taxonomium' => $sgrTaxonomium,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="sgr_taxonomia_delete", methods={"DELETE"})
     */
    public function delete(Request $request, SgrTaxonomia $sgrTaxonomium): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sgrTaxonomium->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($sgrTaxonomium);
            $entityManager->flush();
        }

        return $this->redirectToRoute('sgr_taxonomia_index');
    }
}
