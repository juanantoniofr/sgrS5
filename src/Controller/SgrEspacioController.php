<?php

namespace App\Controller;

use App\Entity\SgrEspacio;
use App\Entity\SgrTermino;

use App\Form\SgrEspacioType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use App\Repository\SgrEspacioRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

// Include paginator interface
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/admin/sgr/espacio")
 */
class SgrEspacioController extends AbstractController
{
    /**
     * @Route("/index/{page}", name="sgr_espacio_index", defaults={"page"=1}, methods={"GET","POST"})
     */
    public function index(Request $request, SgrEspacioRepository $sgrEspacioRepository, PaginatorInterface $paginator, $page): Response
    {

        //espacios
        $espacios = $sgrEspacioRepository->findBy( array(), ['termino' => 'ASC', 'nombre' => 'ASC']);
        //form
        $form = $this->createFormBuilder()
            ->add('termino', EntityType::class, [
                                    'label' => 'Tipo de espacio',
                                    'required' => false,
                                    'placeholder' => 'Todas las Categorías',
                                    'class' => SgrTermino::class,
                                    'choice_label' => 'nombre',
                                    ])
            ->getForm();

        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() )
        {
            //termino
            $termino = $form['termino']->getData();
            if ($termino)
            {
                $espacios = $sgrEspacioRepository->findBy( [ 'termino'=> $termino ], [ 'termino' => 'ASC' , 'nombre' => 'ASC' ]);    
            }
        }

        $pagination = $paginator->paginate(
            $espacios,
            $page,
            10
        );
        
        return $this->render('sgr_espacio/index.html.twig', [
            'sgr_espacios' => $pagination,
            'form' => $form->createView(),
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
     * @Route("/show/{id}", name="sgr_espacio_show", methods={"GET"})
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
     * @Route("/delete/{id}", name="sgr_espacio_delete", methods={"DELETE"})
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

    /**
     * @Route("/ajax/getEspacios", name="sgr_ajax_espacios", methods={"GET"})
     */
    
    public function getEspaciosByTermino(Request $request, $inputType = 'checkBox')
    {
        if ($request->isXmlHttpRequest())
        {
            $sgrEspacios = new ArrayCollection();
            $termino_id = $request->query->get('sgr_filters_sgr_eventos')['termino'];
            $sgrEspacioRepository = $this->getDoctrine()->getRepository(SgrEspacio::class);
            if($termino_id)
                $sgrEspacios = $sgrEspacioRepository->findBy([ 'termino' => $termino_id ], [ 'nombre' => 'asc' ]);
            else 
                $sgrEspacios = $sgrEspacioRepository->findBy( array(),  [ 'nombre' => 'asc' ] );

            //if ($sgrEspacios)
            //{
                switch ($inputType) {
                    
                    case 'select':
                        $template = 'sgr_form/optionsSelect.html.twig';
                        break;
                    case 'checkBox':
                        $template = 'sgr_form/optionsCheckBox.html.twig';
                        break;
                    default:
                        $template = 'sgr_form/optionsSelect.html.twig';
                        break;
                }

                $html['sgrEspacios'] = $this->render($template, [
                                    'options' => $sgrEspacios,
                                    'default' => ['value' => '', 'nombre' => 'Seleccione recurso']
                        ]);    

            return $this->json($html);
            //}
        }
        return new Response('');
    }
}
