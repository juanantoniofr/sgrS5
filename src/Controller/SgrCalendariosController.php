<?php
// src/Controller/SgrCalendariosController.php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SgrCalendariosController extends AbstractController
{
    

    /**
       * @Route("/calendarios.html", name="sgr_calendarios_index")
    */
    public function index()
    {
        
        return $this->render('sgr_calendarios/index.html.twig');
    }
}