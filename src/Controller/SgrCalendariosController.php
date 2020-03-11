<?php
// src/Controller/SgrCalendariosController.php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Repository\SgrEspacioRepository;
use App\Repository\SgrTermnoRepository;

class SgrCalendariosController extends AbstractController
{
    

    /**
       * @Route("/calendarios.html", name="sgr_calendarios_index")
    */
    public function index(SgrEspacioRepository $sgrEspacioRepository, SgrTerminoRepository $sgrTerminoRepository)
    {


        $terminoDefault  = 'Aulas de Docencia';

        return $this->render( 'sgr_calendarios/index.html.twig',
        	[ 
        		'sgr_espacios' => $sgrEspacioRepository->findByFilters($sgrTerminoRepository->findOneBy([ 'name' => $terminoDefault ] )),
        	]
    	);
    }
}