<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\SgrTaxonomiaEspacio;

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
}
