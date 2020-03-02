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
            ->getForm();

        //$form = $this->createForm(Request $request);
        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() ) {

            //Procesar csv
            $file = $form['fileCsv']->getData();

            $aKeysValid = [ 'ES','C.ASIG.','ASIGNATURA','DUR.','GRP.','PROFESOR','F_DESDE','F_HASTA','C.DIA','H_INICIO','H_FIN','AULA'];
            
            
            $keysInvalid = $csv->isValidHeader($file,$aKeysValid);
            //dump($keysInvalid);
            //exit;
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
            
            //$rowsCsv = $csv->getRowsFilterByKeys($file,$aKeysValid);
            //dump($rowsCsv);
            $rowsCsv = new ArrayCollection($csv->getRowsFilterByKeys($file,$aKeysValid));
            //dump($rowsCsv);
            //dump($rowsColletion);
            
            //Solapa en csv
            //Todas las filas del csv que tiene solapammiento
            $filasConSolape = new ArrayCollection();
            //dump($filasConSolape);
            //dump($rowsCsv->current());

            
            //
            $auxRowsCsv = clone $rowsCsv;
            foreach ($rowsCsv as $key => $row) {    
                
                $solapesCsv = new ArrayCollection();//array();

                //Evitar solapamientos consigo mismo
                $auxRowsCsv->removeElement($row);
                //dump($auxRowsCsv);
                //dump($rowsCsv);
                //exit;
                //dump($rowsCsv);
                //exit;
                $solapesCsv = $csv->solapaCsv($auxRowsCsv,$row);
                if (!empty($solapesCsv)){
                    $filasConSolape->add($row['numfilaCsv']);
                    //Para cada fila del csv se añade array con los números de filas con los que solapa
                    $row['validations']['solapaCsv'] = $solapesCsv;
                }
                
                //Se vuelve a añadir para seguir testando solapamientos
                $auxRowsCsv->add($row);
                
                $rowsCsv->set($key,$row);
            }
            //dump($rowsCsv);
            //dump($filasConSolape);
            
            //Validation existAula
            $repository = $this->getDoctrine()->getRepository(SgrEspacio::class);
            /*foreach ($rowsCsv as $key => $row) {
                
                $row['validations']['existAula'] = false;
                if ($repository->exist($row['AULA']))
                    $row['validations']['existAula'] = true;

                $rowsCsv->set($key,$row);
            }*/

            //dump($rowsCsv);
            
            //Validation espacio ocupado (solapa), si existe Aula
            //set titulacion //  asignatura // grupo_asignatura // profesor // 
            $entityManager = $this->getDoctrine()->getManager();
            $repositoryProfesor = $this->getDoctrine()->getRepository(SgrProfesor::class);
            $repositoryAsignatura = $this->getDoctrine()->getRepository(SgrAsignatura::class);
            $repositoryTitulacion = $this->getDoctrine()->getRepository(SgrTitulacion::class);
            $repositoryActividad = $this->getDoctrine()->getRepository(SgrTipoActividad::class);
            $repositoryGrupoAsignatura = $this->getDoctrine()->getRepository(SgrGrupoAsignatura::class);
            foreach ($rowsCsv as $key => $row) {
                
                $sgrEvento = new SgrEvento;
                $sgrEspacio = $repository->exist($row['AULA']);
                if (!$sgrEspacio){
                    $row['validations']['existAula'] = false;
                }
                else {
                    //set Espacio                    
                    $sgrEvento->setEspacio($sgrEspacio);

                    //set f_inicio, f_fin, h_inicio, h_fin
                    $sgrEvento->setFInicio( date_create_from_format('d/m/Y', $row['F_DESDE'], new \DateTimeZone('Europe/Madrid')) );
                    $sgrEvento->setFFin( date_create_from_format('d/m/Y', $row['F_HASTA'], new \DateTimeZone('Europe/Madrid')) );
                    $sgrEvento->setHInicio( date_create_from_format('H:i', $row['H_INICIO'], new \DateTimeZone('Europe/Madrid')) );
                    $sgrEvento->setHFin( date_create_from_format('H:i', $row['H_FIN'], new \DateTimeZone('Europe/Madrid')) );
                    $sgrEvento->setDias([ $row['C.DIA'] ]);
                    
                    //set validation solapa
                    $row['validations']['solapa'] = $evento->setEvento($sgrEvento)->solapa();
                    
                    //update rowsCsv
                    $rowsCsv[$key]=$row;
                    if (!$row['validations']['solapa']){
                        
                        //set Profesor
                        $profesor = $repositoryProfesor->findOneBy([ 'nombre' => $row['PROFESOR'] ]);
                        if($profesor)
                            $sgrEvento->setProfesor($profesor);
                        else
                        {
                            $profesor = new SgrProfesor;
                            $profesor->setNombre($row['PROFESOR']);
                            $entityManager->persist($profesor);
                            $entityManager->flush();

                            $gerEvento->setProfesor($profesor);
                        }

                        
                        //set asignatura && titulacion
                        $asignatura = $repositoryAsignatura->findOneBy([ 'codigo' => $row['C.ASIG.'] ]);
                        
                        if($asignatura){
                            $sgrEvento->setAsignatura($asignatura);
                            $sgrEvento->setTitulacion($repositoryTitulacion->findOneBy([ 'codigo' => substr($row['C.ASIG.'],0,3) ]));
                            //dump($asignatura->getSgrTitulacion());
                        }

                        else
                        {
                            $asignatura = new SgrAsignatura;
                            $asignatura->setCodigo($row['C.ASIG.']);
                            $asignatura->setNombre($row['ASIGNATURA']);
                            $asignatura->setCuatrimestre($row['DUR.']);
                            $asignatura->setSgrTitulacion($repositoryTitulacion->findOneBy([ 'codigo' => substr($row['C.ASIG.'],0,3) ])); //LANZAR EXCEPTION SI NO EXISTE TITULACIÓN
                            $entityManager->persist($asignatura);
                            $entityManager->flush();

                            $sgrEvento->setAsignatura($asignatura);
                            $sgrEvento->setTitulacion($repositoryTitulacion->findOneBy([ 'codigo' => substr($row['C.ASIG.'],0,3) ]));
                        }                        
                        
                        //set Grupo
                        $grupo = $repositoryGrupoAsignatura->findOneBy([ 'sgrAsignatura' => $asignatura->getId() ]);
                        if($grupo){
                            $sgrEvento->setGrupoAsignatura($grupo);
                            $asignatura->addGrupo($grupo);
                            $entityManager->persist($asignatura);
                            $entityManager->flush();   
                        }
                        else{
                            //
                            $grupo = new SgrGrupoAsignatura;
                            $grupo->setNombre($row['GRP.']);
                            $grupo->setSgrAsignatura($asignatura);
                            $grupo->addSgrProfesor($profesor);
                            $entityManager->persist($grupo);
                            $entityManager->flush();
                        }


                        //dump($grupo); 
                        //dump($asignatura->getGrupos()->contains($grupo));
                        //set Actividad

                        $actividad = $repositoryActividad->findOneBy([ 'actividad' => 'Docencia Reglada POD' ]);
                        if($actividad)
                            $sgrEvento->setActividad($actividad);
                        else{
                            //exception 
                        }

                        //dump(substr($row['C.ASIG.'],0,3));
                        //dump($repositoryTitulacion->findOneBy([ 'codigo' => substr($row['C.ASIG.'],0,3) ]));
                        

                        //update rowsSgrEventos
                        $rowsSgrEventos[] = $sgrEvento;
                    }
                    
                }
            }
            
            
            
            dump($filasConSolape);
            dump($rowsSgrEventos);
            dump($rowsCsv);

            exit;
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
