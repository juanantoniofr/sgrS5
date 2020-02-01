<?php

namespace App\Controller;

use App\Entity\TaxonomiaRecursos;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TaxonomiaRecursosController extends AbstractController
{
    /**
     * @Route("/recurso/taxonomia", name="recurso_taxonomia")
     */
    public function index()
    {
        $repository = $this->getDoctrine()->getRepository(TaxonomiaRecursos::class);

        $taxonomias = $repository->findAll();

        return $this->render('taxonomia_recursos/index.html.twig', [
            'taxonomias' => $taxonomias,
        ]);
    }

    /**
    * @Route("/recurso/taxonomia/create", name="recurso_taxonomia_create")
    */
    public function createTaxonomiaRecursos(ValidatorInterface $validator): Response 
    {
    	$entityManager = $this->getDoctrine()->getManager();

    	$taxonomiaRecursos = new TaxonomiaRecursos();
    	$taxonomiaRecursos->setNombre(null);

    	$errors = $validator->validate($taxonomiaRecursos);

    	if (count($errors) > 0){
    		return new Response((string) $errors,400);
    	}
    	//salva en memoria
    	$entityManager->persist($taxonomiaRecursos);

    	//Ejecuta la query sql --> salva en BD
    	$entityManager->flush();

    	return new Response('No pilla errores...');//. $taxonomiaRecursos->getId());
    }

    /**
    * @Route("/recurso/taxonomia/{id}", name="recurso_taxonomia_show")
    */
    public function show($id): Response
    {
    	$taxonomia = $this->getDoctrine()->getRepository(TaxonomiaRecursos::class)->find($id);

    	if (!$taxonomia){
    	
    		throw $this->createNotFoundException('Taxonomia no encontrada con id' . $id);
    	}

    	return new Response('Taxonomia nombre' . $taxonomia->getNombre());
    }

    /**
    * @Route("/recurso/taxonomia/edit/{id}", name="recurso_taxonomia_edit")
    */
    public function update($id)
    {
    	$entityManager = $this->getDoctrine()->getManager();
    	$taxonomia = $entityManager->getRepository(TaxonomiaRecursos::class)->find($id);

    	if (!$taxonomia){

    		throw $this->createNotFoundException("Error Processing Request", 1);
    	}

    	$taxonomia->setNombre('Aula de Teoría - edit');
    	$entityManager->flush();

    	return $this->redirectToRoute('recurso_taxonomia_show', [ 'id' => $taxonomia->getId() ]);

    }
}
