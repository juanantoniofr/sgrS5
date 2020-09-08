<?php
// src/Controller/TestPdfController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



use Nucleos\DompdfBundle\Wrapper\DompdfWrapperInterface;
/**
 * @Route("/admin/sgr/nucleos")
 */

class testNucleosDompdf extends AbstractController
{
    
    /**
       * @Route("/dompdf", name="nucleos_pdf")
    */
    public function dompdf(DompdfWrapperInterface $wrapper)
    {
    	// Retrieve the HTML generated in our twig file
        $html = $this->renderView('dompdf/pdf.html.twig', [
            'title' => "Welcome to our PDF Test"
        ]);

        
        //return $html;
        return $wrapper->getStreamResponse($html, "sgrPDF.pdf",[
            "Attachment" => false
        ]);
        //$response->send();
    }

}