<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\SgrEspacio;

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
    public function createEspacio(): Response
    {
    	$entityManager = $this->getDoctrine()->getManager();

    	$espacio = new SgrEspacio();
    	$espacio->setNombre('Aula de Teoría 2.1');
    	$espacio->setDescripcion('Aula de Teoría planta 2ª');
    	$espacio->setAforo(100);
    	$espacio->setMedios([ 'proyector' => true, 'Pc' => true ]);

    	$entityManager->persist($espacio);

    	$entityManager->flush();

    	return new Response('Espacio creado con éxito. Id ='. $espacio->getId());
    }
    
    /**
    * @Route("/sgr/espacio/show/{id}", name="sgr_espacio_show")
    */
    public function show($id): Response
    {
    	$respository = $this->getDoctrine()->getRepository(SgrEspacio::class);
    	$espacio = $respository->find($id);

    	if (!$espacio){

    		throw $this->CreateNotFoundException("Espacio con id ". $id . " no encontrado");
    		
    	}

    	return new Response( $espacio->getNombre() . '('. $espacio->getId() .')' . 'Descripcion: '. $espacio->getDescripcion());
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
}
