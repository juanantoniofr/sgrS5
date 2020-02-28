<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Form\Extension\Core\Type\FileType;
//use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * @Route("/admin/sgr/uploadCSV")
 */
class SgrUploadCSVController extends AbstractController
{
    /**
     * @Route("/", name="sgr_uploadCSV_index", methods={"GET"})
     */
    public function index(Request $request): Response
    {

        $form = $this->createFormBuilder()
            ->add('fileCsv', FileType::class,['label' => 'Campo requerido','label_attr' => ["data-browse" => "Elegir"] ])
            //->add('save', SubmitType::class, ['label' => 'Upload'])
            ->getForm();

        //$form = $this->createForm(Request $request);
        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() ) {

            //Proceso el csv
            $file = $form['fileCsv']->getData();
            dump($file);
            exit;

            return $this->redirectToRoute('sgr_uploadCSV_index');
        }

        return $this->render('sgr_uploadCSV/index.html.twig',[
            'form' => $form->createView()
        ]);
    }

}
