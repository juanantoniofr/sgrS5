<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class EspacioController extends AbstractController
{
    /**
     * @Route("/espacio", name="espacio")
     */
    public function index()
    {
        return $this->render('espacio/index.html.twig', [
            'controller_name' => 'EspacioController',
        ]);
    }
}
