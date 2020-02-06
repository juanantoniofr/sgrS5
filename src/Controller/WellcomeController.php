<?php
// src/Controller/WellcomeController.php
namespace App\Controller;

use App\Entity\SgrUser;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class WellcomeController extends AbstractController
{
    
    /**
       * @Route("/", name="sgr_wellcome")
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

    /**
       * @Route("/admin/wellcome", name="admin_wellcome")
    */
    public function adminWellcome()
    {
        $site_name = 'Facultad de Geografía e Historia';
        $site_app = 'SGR: Sistema de Gestión de Reservas';

        // sure the user is authenticated first
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // returns your User object, or null if the user is not authenticated
        /** @var \App\Entity\SgrUser $user */
        $user = $this->getUser();

        return $this->render('static/adminWellcome.html.twig', [
            'site_name' => $site_name,
            'site_app' => $site_app,
            'user' => $user,
        ]);
    }
}