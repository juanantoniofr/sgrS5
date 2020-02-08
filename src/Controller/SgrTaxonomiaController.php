<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\SgrTaxonomiaEspacio;

use App\Form\SgrTaxonomiaType;

//form
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class SgrTaxonomiaController extends AbstractController
{
    /**
     * @Route("/admin/taxonomia", name="sgr_taxonomia")
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
    * @Route("/admin/taxonomia/create", name="sgr_taxonomia_create")
    */
    public function create(Request $request): Response
    {
    	$entityManager = $this->getDoctrine()->getManager();

    	$taxonomia = new SgrTaxonomiaEspacio();
    	//$taxonomia->setNombre('');
    	//$taxonomia->setDescripcion('');
    	
    	$form = $this->createForm(SgrTaxonomiaType::class, $taxonomia);

        $form->handleRequest($request);
    	if ($form->isSubmitted() && $form->isValid()) {
	        // $form->getData() holds the submitted values
	        // but, the original `$taxonomia` variable has also been updated
	        $taxonomia = $form->getData();

	        $entityManager = $this->getDoctrine()->getManager();
	        $entityManager->persist($taxonomia);
	        $entityManager->flush();

	        return $this->redirectToRoute('sgr_taxonomia');
    	}

        return $this->render('sgr_taxonomia/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    /**
    * @Route("/admin/taxonomia/show/{id}", name="sgr_taxonomia_show")
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
    * @Route("/admin/taxonomia/edit/{id}", name="sgr_taxonomia_update")
    */
    public function update($id,Request $request): Response
    {
    	$entityManager = $this->getDoctrine()->getManager();
    	$taxonomia = $entityManager->getRepository( SgrTaxonomiaEspacio::class)->find($id);

    	if (!$taxonomia){

    		throw $this->CreateNotFoundException("taxonomia con id ". $id . " no encontrado");
    		
    	}

        $form = $this->createForm(SgrTaxonomiaType::class, $taxonomia);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$taxonomia` variable has also been updated
            $taxonomia = $form->getData();

            // ... perform some action, such as saving the task to the database
        
            $entityManager->persist($taxonomia);
            $entityManager->flush();

            return $this->redirectToRoute('sgr_taxonomia');
        }

    	return $this->render('sgr_taxonomia/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
    * @Route("/admin/taxonomia/delete/{id}", name="sgr_taxonomia_delete")
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
    * @Route("/admin/taxonomia/show/espacios/{id}", name="sgr_taxonomia_show_espacios")
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
