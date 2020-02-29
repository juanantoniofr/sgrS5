<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use App\Service\Csv;
use App\Entity\SgrEspacio;

/**
 * @Route("/admin/sgr/uploadCSV")
 */
class SgrUploadCSVController extends AbstractController
{
    /**
     * @Route("/", name="sgr_uploadCSV_index", methods={"GET","POST"})
     */
    public function index(Request $request, Csv $csv): Response
    {

        $form = $this->createFormBuilder()
            ->add('fileCsv', FileType::class,['label' => 'Campo requerido','label_attr' => ["data-browse" => "Seleccionar archivo"] ])
            ->getForm();

        //$form = $this->createForm(Request $request);
        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() ) {

            //Procesar csv
            $file = $form['fileCsv']->getData();

            //dump($file);
            //dump($arrayFila);
            //exit;

            //$fileName = $file->getClientOriginalName();
            $aKeysValid = [ 'ES','C.ASIG.','ASIGNATURA','DUR.','GRP.','PROFESOR','F_DESDE','F_HASTA','C.DIA','H_INICIO','H_FIN','AULA'];
            $msg = "Procesando cabeccera";
            
            dump($csv->isValidHeader($file,$aKeysValid));
            $rowsCsv = $csv->getRowsFilterByKeys($file,$aKeysValid);
            dump($rowsCsv);
            
            //Validation existAula
            $repository = $this->getDoctrine()->getRepository(SgrEspacio::class);
            foreach ($rowsCsv as $key => $row) {
                
                $row['validations']['existAula'] = false;
                if ($repository->exist($row['AULA']))
                    $row['validations']['existAula'] = true;

                $rowsCsv[$key] = $row;
            }

            dump($rowsCsv);
            //exit;
            //dump($rowsCsv[0]['validations']['existAula']);
            

            //Validation espacio ocupado (solapa)
            foreach ($rowsCsv as $key => $row) {
                
                $row['validations']['solapa'] = true;
                //dump($row['validations']);
                //dump($row['validations']['existAula']);
                //exit;
                //if ($row['validations']['existAula']){
                dump($row['F_DESDE']);
                dump($row['F_HASTA']);
                //dump($row['AULA']);
                dump(new \DateTime($row['F_DESDE']));
                dump(date_create_from_format('d/m/Y', $row['F_DESDE'], new \DateTimeZone('Europe/Madrid')));
                //dump(new \DateTime($row['H_INICIO']));
                //dump(new \DateTime($row['F_HASTA']));
                $f_desde = date_create_from_format('d/m/Y', $row['F_DESDE'], new \DateTimeZone('Europe/Madrid'));
                $f_hasta = date_create_from_format('d/m/Y', $row['F_HASTA'], new \DateTimeZone('Europe/Madrid'));
                $h_inicio = date_create_from_format('H:i', $row['H_INICIO'], new \DateTimeZone('Europe/Madrid'));
                if (!$repository->hasEvento($f_desde, $f_hasta, $h_inicio , $row['AULA']) )
                    $row['validations']['solapa'] = false;
                //}

                $rowsCsv[$key] = $row;
            }
            
            dump($rowsCsv);
            exit;
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
