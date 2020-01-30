<?php

namespace App\Controller;

use App\Entity\TaxonomiaRecursos;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class TaxonomiaRecursosController extends AbstractController
{
    /**
     * @Route("/taxonomia/recursos", name="taxonomia_recursos")
     */
    public function index()
    {
        return $this->render('taxonomia_recursos/index.html.twig', [
            'controller_name' => 'TaxonomiaRecursosController',
        ]);
    }

    /**
    * @Route("/taxonomia/recursos/create", name="taxonomia_recursos_create")
    */
    public function createTaxonomiaRecursos(): Response 
    {
    	$entityManager = $this->getDoctrine()->getManager();

    	$taxonomiaRecursos = new TaxonomiaRecursos();
    	$taxonomiaRecursos->setNombre('Aulas de Docencia');


    	//salva en memoria
    	$entityManager->persist($taxonomiaRecursos);

    	//Ejecuta la query sql --> salva en BD
    	$entityManager->flush();

    	return new Response('salvada nueva taxonomía con id'. $taxonomiaRecursos->getId());
    }
}
