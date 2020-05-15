<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

use App\Service\Csv;
use App\Service\Evento;

use App\Entity\SgrEspacio;
use App\Entity\SgrEvento;
use App\Entity\sgrFechasEvento;
use App\Entity\SgrProfesor;
use App\Entity\SgrAsignatura;
use App\Entity\SgrTitulacion;
use App\Entity\SgrTipoActividad;
use App\Entity\SgrGrupoAsignatura;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


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
            ->add('actividad', EntityType::class,[
                                    'label' => 'Actividad',
                                    'required' => true,
                                    'placeholder' => 'Seleccione Actividad',
                                    'class' => SgrTipoActividad::class,
                                    'choice_label' => 'actividad',
                                    'query_builder' => function (EntityRepository $er) {
                                                            return $er->createQueryBuilder('a')
                                                                      ->where('a.id In (:ids)')
                                                                      ->setParameter('ids', [1, 12])
                                                                      ->orderBy('a.actividad', 'ASC');
                                                        },])
            ->getForm();

        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() ) {


            //Procesar csv
            $file = $form['fileCsv']->getData();

            //actividad
            $actividad = $form['actividad']->getData();//$repositoryActividad->findOneBy([ 'actividad' => 'Docencia Reglada POD' ]);

            $aKeysValid = [ 'ES','C.ASIG.','ASIGNATURA','DUR.','GRP.','PROFESOR','F_DESDE','F_HASTA','C.DIA','H_INICIO','H_FIN','AULA'];
            
            //Validación de claves (columnas CSV, debe estar todas)
            $keysInvalid = $csv->isValidHeader($file,$aKeysValid);
            if ( !empty($keysInvalid) ){
                
                $errors[] = 'Error al procesar las cabeceras del archivo ' . $file->getClientOriginalName();
                
                foreach ($keysInvalid as $key) {
                    $errors[] = 'Columna '. $key . ' no encontrada';
                }

                return $this->render('sgr_uploadCSV/index.html.twig', [ 
                    'errors' => $errors,
                    'form' => $form->createView()
                ]);
            }
            
            //lee el archivo csv y lo guarda en el array $rowsCsv
            $rowsCsv = new ArrayCollection($csv->getRowsFilterByKeys($file,$aKeysValid));
            
            //check valid value para F_DESDE, F_HASTA, H_INICIO, H_FIN, DIA
            //Validations valuesNotValid
            $rowsCsv = $csv->checkValidValues($rowsCsv);

            //Validations existsAULA
            $repositoryEspacio = $this->getDoctrine()->getRepository(SgrEspacio::class);
            $rowsCsv = $csv->checkIfExistEspacio($rowsCsv, $repositoryEspacio);

            //Validations solapaCsv            
            $rowsCsv = $csv->setSolapamientos($rowsCsv);
            
            $entityManager = $this->getDoctrine()->getManager();
                        
            $repositoryProfesor = $this->getDoctrine()->getRepository(SgrProfesor::class);
            $repositoryAsignatura = $this->getDoctrine()->getRepository(SgrAsignatura::class);
            $repositoryTitulacion = $this->getDoctrine()->getRepository(SgrTitulacion::class);
            $repositoryActividad = $this->getDoctrine()->getRepository(SgrTipoActividad::class);
            $repositoryGrupoAsignatura = $this->getDoctrine()->getRepository(SgrGrupoAsignatura::class);
            $rowsSgrEventos = array();
            
            //dump($rowsCsv);
            foreach ($rowsCsv as $key => $row) {
                
                $sgrEvento = new SgrEvento;    
                //Validations existAula pass??
                $espacio = $repositoryEspacio->exist($row['AULA']);
                if($espacio)
                {
                    $row['validations']['existAula'] = true;
                    //$row[$key] = $row;
                }

                //Validations existsTitulación
                //dump($row['ES']);
                //dump($repositoryTitulacion->findOneBy([ 'codigo' => $row['ES'] ]));
                
                $titulacion = $repositoryTitulacion->findOneBy([ 'codigo' => $row['ES'] ]);
                if ($titulacion)
                {							
                	$row['validations']['existTitulacion'] = true;
                    
                    //dump($row['validations']);
                	//$rowsCsv[$key] = $row;
                    //dump($row);
                }
                
                if ($csv->passValidations($row)){
                    //dump($row);

                    $sgrEvento->setEspacio($espacio);
                    //dump($sgrEvento);
                    $evento->setEvento($sgrEvento);
                    $evento->setFechaDesde($row['F_DESDE']);
                    $evento->setFechaHasta($row['F_HASTA']);
                    $evento->setHoraInicio($row['H_INICIO']);
                    $evento->setHoraFin($row['H_FIN']);
                    $evento->setDias($row['C.DIA']);
                   
                    //set validations solapa
                    $row['validations']['solapa'] = $evento->hasSolape();//$evento->solapa();
                    //update rowsCsv
                    $rowsCsv[$key]=$row;
                    
                    //Si no hay solapamientos
                    //ArrayCollection solapamientos ($row['validations']['solapa']) es vacio.
                    if ( ($row['validations']['solapa'])->isEmpty() ){
                        //set Profesor
                        $evento->setProfesor($row['PROFESOR'], $entityManager, $repositoryProfesor);
                        
                        //set titulacion
                        $evento->setTitulacion( $row['C.ASIG.'] , $repositoryTitulacion );
                        //set asignatura 
                        $evento->setAsignatura( $row['C.ASIG.'] , $row['ASIGNATURA'], $row['DUR.'], $entityManager, $repositoryAsignatura, $repositoryTitulacion );
                        
                        //set Grupo
                        $evento->setGrupo($row['GRP.'], $entityManager, $repositoryGrupoAsignatura);

                        //set Actividad
                        //$actividad = $repositoryActividad->findOneBy([ 'actividad' => 'Docencia Reglada POD' ]);
                        if($actividad)
                            $sgrEvento->setActividad($actividad);
                        else{
                            //exception 
                        }


                        //set estado
                        $sgrEvento->setEstado('aprobado'); //pendiente?? Cargar valor de archivo de configuración

                        //set Título
                        $sgrEvento->setTitulo($row['C.ASIG.'] . '-' . $row['ASIGNATURA']);

                        //set User
                        $sgrEvento->setUser($this->getUser());

                        //set Update At
                        $sgrEvento->setUpdatedAt();
                        
                        //update rowsSgrEventos
                        $rowsSgrEventos[] = $sgrEvento;
                    }
                }
                //dump($row);
                $rowsCsv->set($key,$row);
            }
            
            //dump( $rowsCsv );
            //exit;

            if ($rowsSgrEventos)
                
                foreach ($rowsSgrEventos as $sgrEvento) {
                    $evento->setEvento($sgrEvento);
                    //$fechasEvento = $evento->calculateFechasEvento();
                    $fechasEvento = $evento->getAllFechas();                           
                    foreach ($fechasEvento as $dt) {
                        $sgrFechasEvento = new sgrFechasEvento();
                        $sgrFechasEvento->setFecha($dt);
                        $entityManager->persist($sgrFechasEvento);
                        $sgrEvento->addFecha($sgrFechasEvento);
                    }
                    
                    $entityManager->persist($sgrEvento);
                    $entityManager->flush();
            }
            
            
            //return $this->redirectToRoute('sgr_uploadCSV_index', [ 'msg' => $fileName ]);
            return $this->render('sgr_uploadCSV/index.html.twig', [ 
                'eventos' => $rowsSgrEventos,
                'filas' => $rowsCsv,
                'form' => $form->createView()
                ]);
        }

        return $this->render('sgr_uploadCSV/index.html.twig',[
            'form' => $form->createView()
        ]);
    }

}
