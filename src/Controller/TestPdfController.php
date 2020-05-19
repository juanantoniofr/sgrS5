<?php
// src/Controller/TestPdfController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

// Include Dompdf required namespaces
use Dompdf\Dompdf;
use Dompdf\Options;

class TestPdfController extends AbstractController
{
    
    /**
       * @Route("/testpdf", name="test_pdf")
    */
    public function testpdf()
    {

    	// Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        
        //Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        
        //Retrieve the HTML generated in our twig file
        $site_name = 'Facultad de Geografía e Historia';
        $site_app = 'SGR: Sistema de Gestión de Reservas';

        $html = $this->render('static/wellcome.html.twig', [
            'site_name' => $site_name,
            'site_app' => $site_app,
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => false
        ]);
        
    }
}