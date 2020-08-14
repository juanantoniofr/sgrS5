<?php
// src/Controller/TestPdfController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\Common\Collections\ArrayCollection;
use App\Repository\SgrEventoRepository;
use App\Repository\SgrEspacioRepository;
use Core23\DompdfBundle\Wrapper\DompdfWrapperInterface;

use App\Service\Calendario;


class InformesController extends AbstractController
{

	/**
       * @Route("/pdf", name="sgr_genPDF")
    */
    public function genPdf(Request $request, SgrEspacioRepository $sgrEspacioRepository, SgrEventoRepository $sgrEventoRepository, DompdfWrapperInterface $wrapper, Calendario $calendario)
    {

        $request->request->has('sgr_filters_sgr_eventos') ? $dataForm = $request->request->get('sgr_filters_sgr_eventos') : $dataForm = null;

        //dump($request->request->has('sgr_filters_sgr_eventos'));
        //dump($request);

        if (!$dataForm)
        {
            //render error
        }


        //dump($dataForm);

        //get Espacios
        $sgrEspacios = $sgrEspacioRepository->getByTerminoAndEspacios($dataForm['termino'],$dataForm['espacio']);
        //dump($sgrEspacios);

        //set f_inicio y f_fin
        !empty($dataForm['f_inicio']) ? $begin = date_create_from_format('d/m/Y H:i', $dataForm['f_inicio'] . " 00:00", new \DateTimeZone('Europe/Madrid')) : $begin = null;
        !empty($dataForm['f_fin']) ? $end = date_create_from_format('d/m/Y H:i', $dataForm['f_fin'] . " 00:00", new \DateTimeZone('Europe/Madrid')) : $end = null;

        if ( $begin == null || $end == null) 
        {
            //render error
        }

        //set Titulacion
        !empty($dataForm['titulacion']) ? $titulacion = $dataForm['titulacion'] : $titulacion = '';

        $aDiasSemana = array('1','2','3','4','5','6','7');
        $aInicioRangos = array('08:00','08:15','08:30','09:00');
        
        $sgrCalendarios = new ArrayCollection();
        foreach ($sgrEspacios as $sgrEspacio) {
            $sgrEventos = new ArrayCollection();
            $sgrEventosByEspacio = $sgrEventoRepository->getByFilters($titulacion,$dataForm['actividad'],$begin,$end,[ $sgrEspacio ]);
            //dump($sgrEventosByEspacio);
            //exit;
            //, $curso, $id_asignatura, $id_profesor, \DateTime $f_inicio, \DateTime $f_fin,)
            //dump($sgrEventosByEspacio);
            $keyForCalendario = $sgrEspacio->getId();
            foreach ($sgrEventosByEspacio as $evento) {
                
                $aDiasEvento = $evento->getDias();
                foreach ($aDiasEvento as $diaEvento) {

                    if ( $sgrEventos->containsKey($diaEvento) == false ) $sgrEventos->set($diaEvento, new ArrayCollection() );                     
                    $horaInicio = $evento->getHInicio()->format('H:i');
                    $horaFin = $evento->getHFin()->format('H:i');
                    $paso = 15; //minutos.

                    if ( ( $sgrEventos->get($diaEvento) )->containsKey($horaInicio) == false) ( $sgrEventos->get($diaEvento) )->set($horaInicio, new ArrayCollection() );

                    //$sgrEventos->get($diaEvento) -> ArrayCollection donde para cada key = horaInicio tengo un ArrayCollection de eventos

                    //$misEventosAgrupadosPorHoraInicio = $sgrEventos->get($diaEvento);

                    
                    //$concurrencias = $this->getConcurrencias($sgrEventos->get($diaEvento), $evento);
                    $concurrencias = $calendario->getConcurrencias($sgrEventos->get($diaEvento), $evento);  
                    
                    //(( $sgrEventos->get($diaEvento) )->get($horaInicio) )->add([ $evento, 'concurrencias' => $concurrencias]);
                    (( $sgrEventos->get($diaEvento) )->get($horaInicio) )->add( ['evento' => $evento, 'concurrencias' => $concurrencias ] );

                    
                }
            }
            
            $sgrCalendarios->set($keyForCalendario, [$sgrEspacio, $sgrEventos]);

        }
        
        //dump($sgrCalendarios);
        //exit; 

        /*return $this->render('sgr_informes/pdf.html.twig', [
            'calendarios' => $sgrCalendarios,
        ]);*/

    	$html = $this->renderView('sgr_informes/pdf.html.twig', [
            'calendarios' => $sgrCalendarios,
        ]);
    	
        
        $response = $wrapper->getStreamResponse($html, "document.pdf",[
            "Attachment" => false
        ]);
        $response->send();
    }

    /*private function getConcurrencias($eventosDiaSemanaAgrupadosPorHinicio, $miEvento){
        
        $concurrencias = 0;
        if ($eventosDiaSemanaAgrupadosPorHinicio->isEmpty() == false){

            $miHoraInicio = $miEvento->getHInicio();
        
            foreach ($eventosDiaSemanaAgrupadosPorHinicio as $eventos) {
        
                if ( $eventos->isEmpty() == false){  
        
                    foreach ($eventos as $evento) {
                        
                            if ($evento['evento']->getHinicio() == $miHoraInicio || $miHoraInicio < $evento['evento']->getHFin())
                                $concurrencias = $concurrencias + 1;
                        
                    }
                    
                }

            }

        }
        return $concurrencias;
    }
    */

}