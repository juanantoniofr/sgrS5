<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\SgrEspacio;
use App\Entity\SgrTaxonomiaEspacio;

class SgrEspacioController extends AbstractController
{
    /**
     * @Route("/sgr/espacio", name="sgr_espacio")
     */
    public function index()
    {

    	$respository = $this->getDoctrine()->getRepository(SgrEspacio::class);
    	$espacios = $respository->findAll();
        
        return $this->render('sgr_espacio/index.html.twig', [
            'espacios' => $espacios,
        ]);
    }

    /**
    * @Route("/sgr/espacio/create", name="sgr_espacio_create")
    */
    public function create(): Response
    {
		$taxonomia = new SgrTaxonomiaEspacio();
    	$taxonomia->setNombre('Aulas de Docencia');
   	
    	$espacio = new SgrEspacio();
    	$espacio->setNombre('Aula de Teoría 2.1');
    	$espacio->setDescripcion('Aula de Teoría planta 2ª');
    	$espacio->setAforo(100);
    	$espacio->setMedios([ 'proyector' => true, 'Pc' => true ]);
    	$espacio->setTaxonomia($taxonomia);

    	$entityManager = $this->getDoctrine()->getManager();

    	$entityManager->persist($espacio);
    	$entityManager->persist($taxonomia);    	
    	
    	$entityManager->flush();

    	return new Response('Espacio creado con éxito. Id ='. $espacio->getId());
    }
    
    /**
    * @Route("/sgr/espacio/show/{id}", name="sgr_espacio_show")
    */
    public function show($id): Response
    {
    	$espacio = $this->getDoctrine()->getRepository(SgrEspacio::class)->find($id);
    	
    	if (!$espacio){

    		throw $this->CreateNotFoundException("Espacio con id ". $id . " no encontrado");
    		
    	}

    	return new Response( $espacio->getNombre() . '('. $espacio->getTaxonomia()->getNombre() .')');
    }

    /**
    * @Route("/sgr/espacio/edit/{id}", name="sgr_espacio_update")
    */
    public function update($id): Response
    {
    	$entityManager = $this->getDoctrine()->getManager();
    	$espacio = $entityManager->getRepository(SgrEspacio::class)->find($id);

    	if (!$espacio){

    		throw $this->CreateNotFoundException("Espacio con id ". $id . " no encontrado");
    		
    	}

    	$espacio->setDescripcion('Nueva descripcion');
    	$entityManager->persist($espacio);

    	$entityManager->flush();

    	return new Response('Actualización realizada con éxito');
    }

    /**
    * @Route("/sgr/espacio/delete/{id}", name="sgr_espacio_delete")
    */
    public function delete($id): Response
    {
    	$entityManager = $this->getDoctrine()->getManager();
    	$espacio = $entityManager->getRepository(SgrEspacio::class)->find($id);

    	if (!$espacio){

    		throw $this->CreateNotFoundException("Error Processing Request");
    	}

    	$entityManager->remove($espacio);
    	$entityManager->flush();

    	return new Response('Espacio eliminado con éxito');
    }

    /**
    * @Route("/sgr/espacio/update/taxonomia", name="sgr_espacio_nueva_taxonomia")
    */
    public function nuevaTaxonomia(){

    	$taxonomia = new SgrTaxonomiaEspacio();
    	$taxonomia->setNombre('Aulas de Docencia');

    	$entityManager = $this->getDoctrine()->getManager(); 
    	$espacio = $entityManager->getRepository(SgrEspacio::class)->find(1);

    	$espacio->setTaxonomia($taxonomia);

    	$entityManager->persist($espacio);
    	$entityManager->persist($taxonomia);

    	$entityManager->flush();

    	return new Response('Actualizada taxonomia '. $taxonomia->id .' del espacio ' . $espacio->getId());
    }
}
