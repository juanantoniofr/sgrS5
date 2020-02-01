<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
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
    * @Route("/sgr/espacio/create", name="espacio_create")
    */
    public function createEspacio()
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
}
