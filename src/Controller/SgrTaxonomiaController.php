<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\SgrTaxonomiaEspacio;

//form
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class SgrTaxonomiaController extends AbstractController
{
    /**
     * @Route("/sgr/taxonomia", name="sgr_taxonomia")
     */
    public function index()
    {
        $respository = $this->getDoctrine()->getRepository(SgrTaxonomiaEspacio::class);
    	$taxonomias = $respository->findAll();
        
        return $this->render('sgr_taxonomia/index.html.twig', [
            'taxonomias' => $taxonomias,
        ]);
    }

    /**
    * @Route("/sgr/taxonomia/create", name="sgr_taxonomia_create")
    */
    public function create(Request $request): Response
    {
    	$entityManager = $this->getDoctrine()->getManager();

    	$taxonomia = new SgrTaxonomiaEspacio();
    	$taxonomia->setNombre('');
    	$taxonomia->setDescripcion('');
    	
    	$form = $this->createFormBuilder($taxonomia)
            ->add('nombre', TextType::class)
            ->add('descripcion', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Crear Taxonomia'])
            ->getForm();

        return $this->render('sgr_taxonomia/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    /**
    * @Route("/sgr/taxonomia/show/{id}", name="sgr_taxonomia_show")
    */
    public function show($id): Response
    {
    	$respository = $this->getDoctrine()->getRepository(SgrTaxonomiaEspacio::class);
    	$taxonomia = $respository->find($id);

    	if (!$taxonomia){

    		throw $this->CreateNotFoundException("taxonomia con id ". $id . " no encontrado");
    		
    	}

    	return new Response( $taxonomia->getNombre() . '('. $taxonomia->getId() .')' . 'Descripcion: '. $taxonomia->getDescripcion());
    }

    /**
    * @Route("/sgr/taxonomia/edit/{id}", name="sgr_taxonomia_update")
    */
    public function update($id): Response
    {
    	$entityManager = $this->getDoctrine()->getManager();
    	$taxonomia = $entityManager->getRepository( SgrTaxonomiaEspacio::class)->find($id);

    	if (!$taxonomia){

    		throw $this->CreateNotFoundException("taxonomia con id ". $id . " no encontrado");
    		
    	}

    	$taxonomia->setDescripcion('Nueva descripcion');
    	$entityManager->persist($taxonomia);

    	$entityManager->flush();

    	return new Response('Actualización realizada con éxito');
    }

    /**
    * @Route("/sgr/taxonomia/delete/{id}", name="sgr_taxonomia_delete")
    */
    public function delete($id): Response
    {
    	$entityManager = $this->getDoctrine()->getManager();
    	$taxonomia = $entityManager->getRepository(SgrTaxonomiaEspacio::class)->find($id);

    	if (!$taxonomia){

    		throw $this->CreateNotFoundException("Error Processing Request");
    	}

    	$entityManager->remove($taxonomia);
    	$entityManager->flush();

    	return new Response('taxonomia eliminado con éxito');
    }

    /**
    * @Route("/sgr/taxonomia/show/espacios/{id}", name="sgr_taxonomia_show_espacios")
	*/
	public function showEspacios()
	{
		$taxonomia = $this->getDoctrine()->getRepository(SgrTaxonomiaController::class)->find($id);

		$espacios = $taxonomia->getSgrEspacios();
		return $this->render('sgr_taxonomia/index.html.twig', [
            'taxonomias' => $taxonomias,
        ]);
	}
}
