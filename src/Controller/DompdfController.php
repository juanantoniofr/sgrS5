<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Core23\DompdfBundle\Wrapper\DompdfWrapperInterface;
use App\Repository\SgrEspacioRepository;

// Include Dompdf required namespaces
use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * @Route("/admin/sgr/dompdf")
 */
class DompdfController extends AbstractController
{
    /**
     * @Route("/index", name="sgr_index_dompdf", methods={"GET"})}
     */
    public function index(DompdfWrapperInterface $wrapper, SgrEspacioRepository $sgrEspacioRepository)
    {
        

        //dump($dompdf->getOptions());
        //exit;
        
        
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('dompdf/pdf.html.twig', [
            'title' => "Welcome to our PDF Test"
        ]);

        
        //return $htmlTest;
        $response = $wrapper->getStreamResponse($html, "document.pdf",[
            "Attachment" => false
        ]);
        $response->send();
        
        
    }
}