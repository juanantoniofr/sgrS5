<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use App\Service\Csv;
use App\Service\Evento;
use App\Entity\SgrEspacio;
use App\Entity\SgrEvento;

/**
 * @Route("/admin/sgr/uploadCSV")
 */
class SgrUploadCSVController extends AbstractController
{
    /**
     * @Route("/", name="sgr_uploadCSV_index", methods={"GET","POST"})
     */
    public function index(Request $request, Csv $csv, Evento $evento): Response
    {

        $form = $this->createFormBuilder()
            ->add('fileCsv', FileType::class,['label' => 'Campo requerido','label_attr' => ["data-browse" => "Seleccionar archivo"] ])
            ->getForm();

        //$form = $this->createForm(Request $request);
        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() ) {

            //Procesar csv
            $file = $form['fileCsv']->getData();

            $aKeysValid = [ 'ES','C.ASIG.','ASIGNATURA','DUR.','GRP.','PROFESOR','F_DESDE','F_HASTA','C.DIA','H_INICIO','H_FIN','AULA'];
            $msg = "Procesando cabeccera";
            
            if ( !$csv->isValidHeader($file,$aKeysValid) ){
                //guardar errro y volve al formulario upload file;
            }
            
            $rowsCsv = $csv->getRowsFilterByKeys($file,$aKeysValid);
            //dump($rowsCsv);
            
            //Validation existAula
            $repository = $this->getDoctrine()->getRepository(SgrEspacio::class);
            foreach ($rowsCsv as $key => $row) {
                
                $row['validations']['existAula'] = false;
                //dump($repository->exist($row['AULA']));
                if ($repository->exist($row['AULA']))
                    $row['validations']['existAula'] = true;

                $rowsCsv[$key] = $row;
            }

            //dump($rowsCsv);
            
            //Validation espacio ocupado (solapa)
            foreach ($rowsCsv as $key => $row) {
                
                $sgrEvento = new SgrEvento;
                $sgrEspacio = $repository->exist($row['AULA']);
                if (!$sgrEspacio){
                    $row['validations']['existAula'] = false;
                }
                else {
                    $sgrEvento->setEspacio($sgrEspacio);


                    //$sgrEvento->setEspacio($sgrEspacio->setNombre( $row['AULA'] ) );
                    //$sgrEvento->setEspacio($sgrEspacio->setNombre( $row['AULA'] ) );

                    $sgrEvento->setFInicio( date_create_from_format('d/m/Y', $row['F_DESDE'], new \DateTimeZone('Europe/Madrid')) );
                    $sgrEvento->setFFin( date_create_from_format('d/m/Y', $row['F_HASTA'], new \DateTimeZone('Europe/Madrid')) );
                    $sgrEvento->setHInicio( date_create_from_format('H:i', $row['H_INICIO'], new \DateTimeZone('Europe/Madrid')) );
                    $sgrEvento->setHFin( date_create_from_format('H:i', $row['H_FIN'], new \DateTimeZone('Europe/Madrid')) );
                    $sgrEvento->setDias([ $row['C.DIA'] ]);
                    
                    $row['validations']['solapa'] = $evento->setEvento($sgrEvento)->solapa();
                    $rowsCsv[$key]=$row;
                    $rowsSgrEventos[] = $sgrEvento;
                }
            }
            
            //dump($rowsSgrEventos);
            //dump($rowsCsv);
            //exit;
            //return $this->redirectToRoute('sgr_uploadCSV_index', [ 'msg' => $fileName ]);
            return $this->render('sgr_uploadCSV/index.html.twig', [ 
                'msg' => $msg,
                'filas' => $arrayFila,
                'form' => $form->createView()
                ]);
        }

        return $this->render('sgr_uploadCSV/index.html.twig',[
            'form' => $form->createView()
        ]);
    }

}
