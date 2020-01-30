<?php
// src/Controller/WellcomeController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class WellcomeController extends AbstractController
{
    
    /**
       * @Route("/wellcome")
    */
    public function wellcome()
    {
        $site_name = 'Facultad de Geografía e Historia';
        $site_app = 'SGR: Sistema de Gestión de Reservas';

        return $this->render('static/wellcome.html.twig', [
            'site_name' => $site_name,
            'site_app' => $site_app,
        ]);
    }
}