<?php

namespace App\Controller;

use App\Entity\SgrProfesor;
use App\Entity\SgrTitulacion;

use App\Form\SgrProfesorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use App\Repository\SgrProfesorRepository;
use App\Repository\SgrAsignaturaRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// Include paginator interface
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/admin/sgr/profesor")
 */
class SgrProfesorController extends AbstractController
{
    /**
     * @Route("/index/{page}", defaults={"page"=1}, name="sgr_profesor_index", methods={"GET","POST"})
     */
    public function index(Request $request, SgrProfesorRepository $sgrProfesorRepository, SgrAsignaturaRepository $sgrAsignaturaRepository, PaginatorInterface $paginator, $page): Response
    {

        $sgrProfesors = $sgrProfesorRepository->findBy([],['nombre' =>  'Asc']);

        /*dump($sgrProfesors);
        
        //form
        $form = $this->createFormBuilder()
            ->add('titulacion', EntityType::class, [
                                    'label' => 'Elija títulación',
                                    'required' => false,
                                    'placeholder' => 'Elija títulación',
                                    'class' => SgrTitulacion::class,
                                    'choice_label' => 'nombre',
                                    ])
            ->getForm();
        
        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() )
        {
            $sgrProfesors = array();
            //titulación
            $titulacion = $form['titulacion']->getData();
            if ($titulacion)
            {
                $sgrProfesors = array();
                $asignaturas = $sgrAsignaturaRepository->findBy( [ 'sgrTitulacion'=> $titulacion ], [ 'sgrTitulacion' => 'ASC' , 'nombre' => 'ASC' ]);

                //cada asignatura tiene One Or Many grupos
                foreach ($asignaturas as $asignatura)
                {
                        
                        $grupos = $asignatura->getGrupos();

                        //cada grupo tiene One Or Many profesores
                        $profesors = array();
                        foreach ($grupos as $grupo)
                        {
                            $profesors[] = $grupo->getSgrProfesors();   
                        }
                        //Si hay profesores, lo añadimos al array $profesors
                        if (!empty($profesors))
                            foreach ($profesors as $profesor)
                            {
                                dump($profesor->initialize());
                                $sgrProfesors[] = $profesor;
                            }
                }    
            }

            dump($sgrProfesors);
            exit;
        }*/

        $pagination = $paginator->paginate(
            $sgrProfesors,
            $page,
            10
        );

        return $this->render('sgr_profesor/index.html.twig', [
            'sgr_profesors' => $pagination,
            //'form' => $form->createView(),
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
