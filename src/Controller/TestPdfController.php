<?php
// src/Controller/TestPdfController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

//use Knp\Snappy\Pdf;


/**
 * @Route("/admin/sgr/snappy")
*/
class TestPdfController extends AbstractController
{
    /*private $snappy;

    function __construct(Pdf $snappy){

        $this->snappy = $snappy;
    }
    */

    /**
       * @Route("/test", name="snappy_pdf")
    */
    public function test()
    {

    /*
        $html = $this->renderView('dompdf/pdf.html.twig', [
            'title' => 'asdasdasdasd'
        ]);
    
        return new Response( $this->snappy->getOutputFromHtml($html), 200, array(
            'Content-Type'          => 'application/pdf',
            'Content-Disposition'   => 'inline; filename="export.pdf"'
        )
    );
    */
        
    }
}