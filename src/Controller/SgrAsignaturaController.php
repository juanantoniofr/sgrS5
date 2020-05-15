<?php

namespace App\Controller;

use App\Entity\SgrAsignatura;
use App\Entity\SgrGrupoAsignatura;
use App\Entity\SgrTitulacion;


use App\Form\SgrAsignaturaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


use App\Repository\SgrAsignaturaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// Include paginator interface
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/admin/sgr/asignatura")
 */
class SgrAsignaturaController extends AbstractController
{
    /**
     * @Route("/index/{page}", name="sgr_asignatura_index", defaults={"page"=1}, methods={"GET","POST"})
     */
    public function index(Request $request, SgrAsignaturaRepository $sgrAsignaturaRepository, PaginatorInterface $paginator, $page): Response
    {

        $asignaturas = $sgrAsignaturaRepository->findBy([],['sgrTitulacion' =>  'Asc','nombre' => 'Asc']);

        //form
        $form = $this->createFormBuilder()
            ->add('titulacion', EntityType::class, [
                                    'label' => 'Elija títulación',
                                    'required' => false,
                                    'placeholder' => 'Todas las titulaciones',
                                    'class' => SgrTitulacion::class,
                                    'choice_label' => 'nombre',
                                    ])
            ->getForm();
        
        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() )
        {
            //titulación
            $titulacion = $form['titulacion']->getData();
            if ($titulacion)
            {
                $asignaturas = $sgrAsignaturaRepository->findBy( [ 'sgrTitulacion'=> $titulacion ], [ 'sgrTitulacion' => 'ASC' , 'nombre' => 'ASC' ]);    
            }
        }

        $pagination = $paginator->paginate(
            $asignaturas,
            $page,
            10
        );

        return $this->render('sgr_asignatura/index.html.twig', [
                    'pagination' => $pagination,
                    'form' => $form->createView(),
        ]);


        /*return $this->render('sgr_asignatura/index.html.twig', [
            'sgr_asignaturas' => $sgrAsignaturaRepository->findAll(),
        ]);*/
    }

    /**
     * @Route("/new", name="sgr_asignatura_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $sgrAsignatura = new SgrAsignatura();
        
        //$grupo = new SgrGrupoAsignatura();
        
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
