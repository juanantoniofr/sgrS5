<?php
// src/Controller/SgrCalendariosController.php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use App\Repository\SgrEspacioRepository;
use App\Repository\SgrEventoRepository;
use App\Repository\SgrTerminoRepository;
use App\Repository\SgrFechasEventoRepository;

use App\Form\SgrFiltersSgrEventosType;
use App\Form\SgrEventoType;
use App\Entity\SgrEvento;
use App\Entity\SgrFechasEvento;
use App\Service\Calendario;
use App\Service\Evento;

use Core23\DompdfBundle\Wrapper\DompdfWrapperInterface;
use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * @Route("/admin/sgr/calendario")
 */
class SgrCalendariosController extends AbstractController
{
    
    private $session;


    /**
       * @Route("/vista/test/{view}/{espacioId}/{action}/{f_inicio}/{f_fin}", name="sgr_test_calendarios_vista", methods={"GET","POST"}), defaults={"view": "anual", "espacioId": "","action" : "show", "f_inicio" : "", "f_fin" : ""}
    */
    //sustituye a la función index
    public function test(Request $request, SgrEspacioRepository $sgrEspacioRepository, SgrEventoRepository $sgrEventoRepository,  Calendario $calendario, sgrFechasEventoRepository $sgrFechasEventoRepository, sgrTerminoRepository $sgrTerminoRepository, SessionInterface $session, DompdfWrapperInterface $wrapper, String $view = 'anual', String $espacioId = '',String $action = 'show', String $f_inicio = '', String $f_fin = '')
    {

        $template = $this->newGetTemplate($view);

        //form filter eventos
        $form = $this->createForm(SgrFiltersSgrEventosType::class);
        $form->handleRequest($request);

        //*********************
        // Set defaults values
        //*********************
        //default value: Aulas de docencia
        $termino = 2;           
        // default values (Array ids) for espacios
        $espacios = new ArrayCollection($session->get('idsEspacios', Array()));
                 
        $titulacion = '';
        $actividad = '';
        //dump($view);
        empty($view) ? $view = "anual" : $view;
        empty($action) ? $action = "show" : $action;

        if ( $form->isSubmitted() && $form->isValid() )
        {
            $data = $form->getData();
            //dump($view);
            //dump('controller');
            //dump($data);
           
            $termino = $data['termino']->getId();
            $espacios = $data['espacio'];
            //dump('data espacio');
            //dump($data['espacio']);
            $data['f_inicio'] == null ? $begin = $this->newGetBegin($view, $action, $data['f_inicio']) : $begin = $this->getFromFormat($data['f_inicio']);
            $data['f_fin'] == null ? ( ( $view == 'diaria' ) ? $end = $begin : $end = $this->newGetEnd($view, $action, $data['f_inicio'], $data['f_fin']) ) : $end = $this->getFromFormat($data['f_fin']);
            //dump($action);
            //dump($begin);
            //dump($end);
            $session->set('filtros', $this->setFiltros($data));    
        }
        else 
        {
            empty($f_inicio) ? $begin = $this->newGetBegin($view, $action, $f_inicio) : $begin = $this->getFromFormat($f_inicio);
            //dump($action);

            empty($f_fin) ? $end = $this->newGetEnd($view, $action, $f_inicio, $f_fin) : $end = $this->getFromFormat($f_fin);

            $session->set('filtros', $this->setDefaultsFiltros($termino, $sgrTerminoRepository, $sgrEspacioRepository));    
        } 

        //get Espacios
        //dump($espacios);
        $sgrEspacios = $sgrEspacioRepository->getByTerminoAndEspacios($termino,$espacios->toArray());
        
        $sgrCalendarios = new ArrayCollection();
        foreach ($sgrEspacios as $sgrEspacio) {
            $sgrEventos = new ArrayCollection();
            $sgrEventosByEspacio = $sgrEventoRepository->getByFilters($titulacion,$actividad,$begin,$end,[ $sgrEspacio ]);
            
            $keyForCalendario = $sgrEspacio->getId();
            foreach ($sgrEventosByEspacio as $evento) {
                
                $aDiasEvento = $evento->getDias();
                foreach ($aDiasEvento as $diaEvento) {

                    if ( $sgrEventos->containsKey($diaEvento) == false ) $sgrEventos->set($diaEvento, new ArrayCollection() );                     
                    $horaInicio = $evento->getHInicio()->format('H:i');
                    $horaFin = $evento->getHFin()->format('H:i');
                    $paso = 15; //minutos.

                    if ( ( $sgrEventos->get($diaEvento) )->containsKey($horaInicio) == false) ( $sgrEventos->get($diaEvento) )->set($horaInicio, new ArrayCollection() );

                    $concurrencias = $calendario->getConcurrencias($sgrEventos->get($diaEvento), $evento); 
                    
                    (( $sgrEventos->get($diaEvento) )->get($horaInicio) )->add( ['evento' => $evento, 'concurrencias' => $concurrencias ] );
                }
            }
            $sgrCalendarios->set($keyForCalendario, [$sgrEspacio, $sgrEventos, count($sgrEventosByEspacio)]);
        }
        
        //dump($template);
        //dump($sgrCalendarios);
        //dump($sgrCalendarios->key());
        //dump($espacioId);
        //dump($view);
        //dump($session->get('filtros', Array()));
        //dump($sgrEspacios);
        //exit;

        //set Values to session
        $this->setValuesToSession($session,'idTermino',$termino);
        
        $idsEspacios = $this->getIdToEspacios($sgrEspacios);
        //dump($idsEspacios);
        $this->setValuesToSession($session,'idsEspacios',$idsEspacios);

        //dump($request->query->get('genPDF'));
        //dump($request->query->get('genPDF'));
        //exit;
        if ( $request->query->get('genPDF') )
        {
            // return like html 
            /*return $this->render('sgr_informes/pdf.html.twig', [
                'calendarios' => $sgrCalendarios,
            ]);*/


            //return like PDF download file
            $html = $this->renderView('sgr_informes/pdf.html.twig', [
                'calendarios' => $sgrCalendarios,
            ]);
            
            //$html = '<h1>Sample Title</h1><p>Lorem Ipsum</p>';
            /*return $this->render('sgr_informes/pdf.html.twig', [
                'calendarios' => $sgrCalendarios,
            ]);*/
            //dump($html);
            //exit;
            $response = $wrapper->getStreamResponse($html, "sgr_Pdf.pdf",[
                "Attachment" => false
            ]);
            $response->send();
        }
        //dump($sgrCalendarios);
        
        



        return $this->render( $template,[ 
            'form'  => $form->createView(),
            'calendarios' => $sgrCalendarios,
            'data'  => [ 'begin' => $begin , 'end' => $end , 'actividad' => '' ],
            'filtros' => $session->get('filtros', Array()),
            'view' => $view,
            'month' => (new \DateTime('now',new \DateTimeZone('Europe/Madrid')) )->format('m'), 
            'year' => (new \DateTime('now',new \DateTimeZone('Europe/Madrid')) )->format('Y'),
            'showFilters' => true, //$this->session->get('showFilters', true),
            'tabActive' => $this->newGetSelectedTab($espacioId,$sgrEspacios),
        ]);
    
    }

    private function getIdToEspacios(Array $sgrEspacios){

        $idsEspacios = Array();

        if ( !empty($sgrEspacios) )
        {
            foreach ($sgrEspacios as $espacio) {
                $idsEspacios[] = $espacio->getId(); 
            }
        }

        return $idsEspacios;
    }

    private function setValuesToSession($session,$key,$value){

        return $session->set($key, $value);
    }

    private function newGetSelectedTab($espacioId,$sgrEspacios){

        //dump($sgrEspacios);
        //exit;   
        $result = 0;
        is_array($sgrEspacios) ? $espacios = new ArrayCollection($sgrEspacios) :  $espacios = $sgrEspacios;

        if ($espacios->isEmpty() == false){
            $tabDefault = $espacios->first()->getId();
            $tab = '';
            foreach ($espacios as $espacio) 
                $espacioId == $espacio->getId() ? $tab = $espacio->getId() : null;
        
            $tab != '' ? $result = $tab : $result = $tabDefault;

            
        }

        return $result;
    }


    private function newGetTemplate($view)
    {

        /* testViewDay es una vista semanal mientras hacemos pruebas */
        switch ($view) 
        {
            case 'semanal':
                //$template = 'sgr_calendarios/viewWeek.html.twig';
                $template = 'sgr_calendarios/testViewDay.html.twig';
                break;
            case 'mensual':
                $template = 'sgr_calendarios/viewMonth.html.twig';
                break;    
            case 'anual':
                $template = 'sgr_calendarios/testViewDay.html.twig';
                break;
            case 'diaria':
            default:
                $template = 'sgr_calendarios/viewDay.html.twig';
                break;
        }

        return $template;
    }
    
    private function newGetEnd($view = 'diaria', $action = 'show', $f_inicio = '', $f_fin = '')
    {

        $current_month = (new \DateTime('now',new \DateTimeZone('Europe/Madrid')) )->format('m');
        $current_year = (new \DateTime('now',new \DateTimeZone('Europe/Madrid')) )->format('Y');

        $fecha = $this->getFromFormat($f_inicio);
        switch ($view) {
            case 'semanal':
                $end = $fecha->modify('Sunday this week');
                //$action == 'show' ? $end = new \DateTime('Sunday this week', new \DateTimeZone('Europe/Madrid')) : $end = $fecha->modify('Sunday this week');
                break;
            case 'mensual':
                
                 $action == 'show' ? $end = new \DateTime('last day of this month midnight', new \DateTimeZone('Europe/Madrid')) : $end = $fecha->modify('last day of this month midnight');
                
                break;    
            case 'anual':
                if ( $action == 'show' )
                    $current_month > 8 ?  $end = new \DateTime('31-8-'.($current_year+1), new \DateTimeZone('Europe/Madrid')) : $end = new \DateTime('31-8-'.$current_year, new \DateTimeZone('Europe/Madrid'));
                else
                    !empty($f_fin) ? $end = date_create_from_format('d/m/Y H:i', $f_fin . " 00:00", new \DateTimeZone('Europe/Madrid')) : $end = new \DateTime('31-8-'.$current_year, new \DateTimeZone('Europe/Madrid'));
                    
                break;
            case 'diaria':
            default:
                $action == 'show' ? $end = new \DateTime('today', new \DateTimeZone('Europe/Madrid')) : $end = $fecha;
                break;
        }

        return $end;
    }

    private function newGetBegin($view = 'diaria', $action = 'show', $f_inicio = '')
    {

        $current_month = (new \DateTime('now',new \DateTimeZone('Europe/Madrid')) )->format('m');
        $current_year = (new \DateTime('now',new \DateTimeZone('Europe/Madrid')) )->format('Y');

        $fecha = $this->getFromFormat($f_inicio);
        //dump($fecha);
        switch ($view) {
            case 'semanal':
                
                $action == 'show' ? $begin = new \DateTime('Monday this week', new \DateTimeZone('Europe/Madrid')) : $begin = $fecha->modify('Monday this week');
                break;
            case 'mensual':
                
                $action == 'show' ? $begin = new \DateTime('first day of this month midnight', new \DateTimeZone('Europe/Madrid')) : $begin = $fecha->modify('first day of this month midnight');
                break;    
            case 'anual':
                
                if ( $action == 'show' )
                    $current_month > 8 ?  $begin = new \DateTime('1-9-'.$current_year, new \DateTimeZone('Europe/Madrid')) : $begin = new \DateTime('1-9-'.($current_year-1), new \DateTimeZone('Europe/Madrid')); 
                else
                    $begin = $fecha;
                break;
            case 'diaria':
                $action == 'show' ? $begin = new \DateTime('today', new \DateTimeZone('Europe/Madrid')) : $begin = $fecha;
                break;
            default:
                $begin = new \DateTime('today', new \DateTimeZone('Europe/Madrid'));
                break;
        }
        
        return $begin;
    }    

    private function getFromFormat($fecha)
    {

        $result = new \DateTime('now', new \DateTimeZone('Europe/Madrid'));

        date_create_from_format('d/m/Y H:i', $fecha . " 00:00", new \DateTimeZone('Europe/Madrid')) != null ? $result = date_create_from_format('d/m/Y H:i', $fecha . " 00:00", new \DateTimeZone('Europe/Madrid')) : null;
        date_create_from_format('Y-m-d H:i', $fecha . " 00:00", new \DateTimeZone('Europe/Madrid')) != null ? $result = date_create_from_format('Y-m-d H:i', $fecha . " 00:00", new \DateTimeZone('Europe/Madrid')) : null;

        return $result;
    }

    private function setFiltros($data)
    {
        $filtros = array();
        
        $data['termino'] != null ? $filtros['Tipo'] = $data['termino']->getNombre() : true;
        $data['espacio'] != null ? $filtros['Espacios'] = $data['espacio'] : true;
        $data['actividad'] != null ? $filtros['Actividad'] = $data['actividad']->getActividad() : true;
        $data['titulacion'] != null ? $filtros['Titulacion'] = $data['titulacion']->getNombre() : true;
        $data['asignatura'] != null ? $filtros['Asignatura'] = $data['asignatura']->getNombre() : true;
        $data['profesor'] != null ? $filtros['Profesor'] = $data['profesor']->getNombre() : true;
        
        return $filtros;
    }

    private function setDefaultsFiltros($termino, $sgrTerminoRepository, $sgrEspacioRepository)
    {
        $filtros = array();
        
        $filtros['Tipo'] = $sgrTerminoRepository->find($termino)->getNombre();
        $filtros['Espacios'] = $sgrEspacioRepository->findBy( [ 'termino' => $termino] );
        
        return $filtros;
    }
    
    /**
       * @Route("/vista/{view}/{action}/{f_inicio}", name="sgr_calendarios_vista", methods={"GET","POST"}), defaults={"view": "diaria", "action" : "show", "f_inicio" : ""}
    */
    public function index(Request $request, SgrEspacioRepository $sgrEspacioRepository, sgrFechasEventoRepository $sgrFechasEventoRepository, sgrTerminoRepository $sgrTerminoRepository, SessionInterface $session, String $view = 'diaria', String $action = 'show', String $f_inicio = ''){
        
        //Session
        $this->session = $session;
        //form evento
        $formEvento = $this->createForm(SgrEventoType::class);

        //form filter eventos
        $form = $this->createForm(SgrFiltersSgrEventosType::class);
        $form->handleRequest($request);

        $filtros = array();
        
        $current_month = (new \DateTime('now',new \DateTimeZone('Europe/Madrid')) )->format('m');
        $current_year =  (new \DateTime('now',new \DateTimeZone('Europe/Madrid')) )->format('Y');

        //Set view at session
        isset($view) ? $this->session->set('view', $view) : $this->session->set('view', 'diaria');

        //action
        // none --> no submit, referer link menu
        // save --> no submit, referer edit, new or delete
        // filter --> submit form
        
        if (!$form->isSubmitted())
        {

            $template = $this->getTemplate($view);

            //dump($f_inicio);
            //exit;
            $begin = $this->getBegin($view, $current_month, $current_year, $action, $f_inicio);
            $end = $this->getEnd($view, $current_month, $current_year, $action, $f_inicio);

            //dump($begin);
            //dump($end);
            //set f_inicio and f_fin
            $this->session->set( 'f_inicio', $begin->format('d/m/Y'));
            $this->session->set( 'f_fin', $end->format('d/m/Y'));
            
            //Si no submit tomamos datos filtros de session si existen
            //estas variables se usan en SgrFiltersSgrEventosType.php para determinar el valor por defecto cuando se envía formulario vacio (al cargar la página por primera vez)
            $id_actividad = $this->session->get('idActividad', null);
            $filtros['Actividad'] = $this->session->get('nombreActividad', null);

            $id_titulacion = $this->session->get('idTitulacion', null);
            $filtros['Titulacion'] = $this->session->get('nombreTitulacion', null);

            $id_asignatura = $this->session->get('idAsignatura', null);
            $filtros['Asignatura'] = $this->session->get('nombreAsignatura', null);
                
            $id_profesor = $this->session->get('idProfesor', null);
            $filtros['Profesor'] = $this->session->get('nombreProfesor', null);

            $id_termino = $this->session->get('idTermino', 2); //Si no está definido, el término por defecto es Aulas de docencia
            $this->session->set('idTermino',$id_termino);
            $filtros['Categoria'] = $id_termino; //$this->session->get('termino', null);

            //$ids_espacios --> No definido, sería un array de ids 
            $filtros['Espacios'] = $this->session->get('espacios', []);

            
            //Filtramos
            $sgrFechasEvento = $sgrFechasEventoRepository->findBetween($begin, $end);
            
            $sgrFechasEvento = $this->filterByActividad($sgrFechasEvento, $this->session->get('nombreActividad', null));
            $sgrFechasEvento = $this->filterByTitulacion($sgrFechasEvento, $this->session->get('nombreTitulacion', null));
            $sgrFechasEvento = $this->filterByAsignatura($sgrFechasEvento, $this->session->get('nombreAsignatura', null));
            $sgrFechasEvento = $this->filterByProfesor($sgrFechasEvento, $this->session->get('nombreProfesor', null));

            //Por espacios 
            //1. todos los espacios // default sgrEspacios con idtermino = 2 -Aulas de docencia-
            $sgrEspacios = $sgrEspacioRepository->findBy(
            												['termino' => 2],
            												['nombre' => 'ASC']
            											);
        
            //2. Filtro por categoría (Aulas de docencia, aulas de teoría, ....) 
            //dump($sgrEspacios);
            if ( $this->session->get('termino') != null )
            {
                $sgrEspacios = $sgrEspacioRepository->findBy([ 'termino' => $this->session->get('idTermino') ]);

                $listIdEspacios = $this->getIdEspacios($sgrEspacios);
                $this->session->set('idEspacios',$listIdEspacios);

                $listNombreEspacios = $this->getNombreEspacios($sgrEspacios);
                $this->session->set('espacios', implode(', ', $listNombreEspacios) );    
            }
            //3. Filtro por espacios concreto (Aula XX1, Aula II....)
            //dump('no submit && session->termino=null ');
            //dump($sgrEspacios);
            //dump($this->session->get('termino', null));
            if ( $this->session->get('idEspacios') != null && empty($this->session->get('idEspacios')) === false )
            {
                //dump('esto no debe salir');
                //dump($this->session->get('idEspacios', null));
                //dump($this->session->get('idEspacios'));
                /*foreach ($sgrEspacios as $espacio) {
                    dump($espacio->getId());
                }*/
                $sgrEspacios = $this->filterByEspaciosIdInSession($sgrEspacios, $this->session->get('idEspacios') );
            }
            //dump($this->session->get('idEspacios'));
            //dump($this->session->get('termino'));
            //dump($sgrEspacios);
            //dump('no submit');
        }
            
        if ($form->isSubmitted())
        {
            if ($form->isValid()) 
            {
                //dump('submit');
                
	            $data = $form->getData();
                
                $action = 'filter';
                
                //f_inicio
                $data['f_inicio'] ? $f_inicio = $data['f_inicio'] : $f_inicio = '';
                
                //f_fin
                $data['f_fin'] ? $f_fin = $data['f_fin']  : $f_fin = '';
                
                $begin = $this->getBegin($view, $current_month, $current_year, $action, $f_inicio);
                $end = $this->getEnd($view, $current_month, $current_year, $action, $f_inicio,$f_fin);
                
                //set f_inicio and f_fin
                $this->session->set( 'f_inicio', $begin->format('d/m/Y'));
                $this->session->set( 'f_fin', $end->format('d/m/Y'));

                $template = $this->getTemplate($view);
                

                //información para $view == mensual
                $current_month = $begin->format('m'); 
                $current_year = $begin->format('Y');

                //filter by fechas
                $sgrFechasEvento = $sgrFechasEventoRepository->findBetween($begin, $end);
                
                //filter by actividad
                $data['actividad'] ? $this->session->set('nombreActividad', $data['actividad']->getActividad()) : $this->session->set('nombreActividad', null);
                $data['actividad'] ? $this->session->set('idActividad',$data['actividad']->getId()) : $this->session->set('idActividad', null);
                $data['actividad'] ? $filtros['Actividad'] = $data['actividad']->getActividad() : $filtros['Actividad'] = null;
                $data['actividad'] ? $sgrFechasEvento = $this->filterByActividad($sgrFechasEvento, $data['actividad']) : null;

                //filter by titulacion
                $data['titulacion'] ? $this->session->set('nombreTitulacion',$data['titulacion']->getNombre()) : $this->session->set('nombreTitulacion', null);
                $data['titulacion'] ? $this->session->set('idTitulacion',$data['titulacion']->getId()) : $this->session->set('idTitulacion', null);
                $data['titulacion'] ? $filtros['Titulacion'] = $data['titulacion']->getNombre() : $filtros['Titulacion'] = null;
                $data['titulacion'] ? $sgrFechasEvento = $this->filterByTitulacion($sgrFechasEvento, $data['titulacion']) : null;

				//filter by asignatura
				$data['asignatura'] ? $this->session->set('nombreAsignatura',$data['asignatura']->getNombre()) : $this->session->set('nombreAsignatura', null);
                $data['asignatura'] ? $this->session->set('idAsignatura',$data['asignatura']->getId()) : $this->session->set('idAsignatura', null);
                $data['asignatura'] ? $filtros['Asignatura'] = $data['asignatura']->getNombre() : $filtros['Asignatura'] = null;
                $data['asignatura'] ? $sgrFechasEvento = $this->filterByAsignatura($sgrFechasEvento, $data['asignatura']) : null;

                //filter by profesor
				$data['profesor'] ? $this->session->set('nombreProfesor',$data['profesor']->getNombre()) : $this->session->set('nombreProfesor', null);
                $data['profesor'] ? $this->session->set('idProfesor',$data['profesor']->getId()) : $this->session->set('idProfesor', null);
                $data['profesor'] ? $filtros['Profesor'] = $data['profesor']->getNombre() : $filtros['Profesor'] = null;
                $data['profesor'] ? $sgrFechasEvento = $this->filterByProfesor($sgrFechasEvento, $data['profesor']) : null;
                
                //All espacios. (y a continuación filtramos por termino y/o espacios concretos checkboxes en formulario)
                $sgrEspacios = $sgrEspacioRepository->findAll();
                
                //filter by termino (Categorías: Aulas de informática, solo Aulas de docencia,....)
                $data['termino'] ? $this->session->set('termino',$data['termino']->getNombre()) : $this->session->set('termino', null);
                $data['termino'] ? $this->session->set('idTermino',$data['termino']->getId()) : $this->session->set('idTermino', '');
                $data['termino'] ? $filtros['Categoria'] = $data['termino']->getNombre() : $filtros['Categoria'] = null;

                if ($data['termino'])
                {
                    $sgrEspacios = $sgrEspacioRepository->findBy([ 'termino' => $data['termino'] ]);

                    $listIdEspacios = $this->getIdEspacios($sgrEspacios);
                    $this->session->set('idEspacios',$listIdEspacios);

                    $listNombreEspacios = $this->getNombreEspacios($sgrEspacios);
                    $this->session->set('espacios', implode(', ', $listNombreEspacios) ); 
                }
                
                //Filtro por espacios
                //dump($data['espacio']);
                //dump($data['termino']);
                if ($data['espacio']->isEmpty() != true)
                {
                    $listIdEspacios = $this->getIdEspacios($sgrEspacios);
                    $this->session->set('idEspacios',$listIdEspacios);

                    $listNombreEspacios = $this->getNombreEspacios($sgrEspacios);
                    $this->session->set('espacios', implode(', ', $listNombreEspacios) ); 

                    $sgrEspacios = $data['espacio']->toArray();
                }
                //dump($this->session->get('idEspacios'));
                //dump($this->session->get('termino'));
            } //fin sumbit->isValid()
            else
            {
                //******
                // No valid form
                //********

                //dump($form->getData());//['f_inicio']);
                //dump($form->getErrors(true));
                foreach ($form->getErrors(true) as $error) {
                    //dump($error->getMessage());
                }
            }
        }
        
        $filtros['Espacios'] = $this->session->get('espacios');

        $aCalendarios = array();
        $aCalendarios = $this->getCalendarios($sgrEspacios, $sgrFechasEvento);

        //dump('data->ui');
        //dump(isset($data['ui']));
        if ( isset($data['ui']) )
        {
           $ui = json_decode($data['ui'],true);
            
            if ( isset($ui['filters']) )
            {
                $ui['filters'] ? $showFilters = true : $showFilters = false;
                $this->session->set('showFilters', $ui['filters']);
            }

            if ( isset($ui['tabActive']) )
            {
                $tabActive = $ui['tabActive'];

                is_array($sgrEspacios) ? $findEspacios = new ArrayCollection($sgrEspacios) : $findEspacios = $sgrEspacios;
                $tab = $this->getSelectedTab($tabActive,$findEspacios);
                $this->session->set('tabActive', $tab);
            }
        }
        else
        {

            // the second argument is the value returned when the attribute doesn't exist
            $showFilters = $this->session->get('showFilters', true);
            //dump('ui=false');
            //dump($sgrEspacios);
            is_array($sgrEspacios) ? $findEspacios = new ArrayCollection($sgrEspacios) : $findEspacios = $sgrEspacios;

            $tab = $this->getSelectedTab($this->session->get('tabActive', ''),$findEspacios);
            $this->session->set('tabActive', $tab);
 
        }
        
        //dump('calendarios controller');
        //dump($view);

        return $this->render( $template,[ 
            'form'  => $form->createView(),
            'formEvento'  => $formEvento->createView(), 
            'aCalendarios' => $aCalendarios,
            'data'  => [ 'begin' => $begin , 'end' => $end , 'actividad' => $this->session->get('nombreActividad', null) ],
            'filtros' => $filtros,
            'view' => $view,
            'month' => $current_month, 
            'year' => $current_year,
            'showFilters' => $this->session->get('showFilters', true),
            'tabActive' => $this->session->get('tabActive'),   
            //'numDaysView' => (int) $begin->diff($end)->format('%d'),
        ]);
    }

    private function getEnd($view, $current_month, $current_year, $action, $f_inicio, $f_fin = ''){

        //dump('getEnd');
        //dump($action);
        //dump($f_inicio);
        //format f_inicio = d/m/Y
        $fecha = $this->normalize($f_inicio);
        switch ($view) {
            case 'semanal':

                $action == 'show' ? $end = new \DateTime('Sunday this week', new \DateTimeZone('Europe/Madrid')) : $end = $fecha->modify('Sunday this week');
                break;
            case 'mensual':
                
                 $action == 'show' ? $end = new \DateTime('last day of this month', new \DateTimeZone('Europe/Madrid')) : $end = $fecha->modify('last day of this month');
                
                break;    
            case 'anual':
                if ( $action == 'show' )
                    $current_month > 8 ?  $end = new \DateTime('31-8-'.($current_year+1), new \DateTimeZone('Europe/Madrid')) : $end = new \DateTime('31-8-'.$current_year, new \DateTimeZone('Europe/Madrid'));
                else
                    !empty($f_fin) ? $end = date_create_from_format('d/m/Y H:i', $f_fin . " 00:00", new \DateTimeZone('Europe/Madrid')) : $end = new \DateTime('31-8-'.$current_year, new \DateTimeZone('Europe/Madrid'));
                    
                break;
            case 'diaria':
            default:
                $action == 'show' ? $end = new \DateTime('now', new \DateTimeZone('Europe/Madrid')) : $end = $fecha;
                break;
        }

        return $end;
    }

    private function normalize($fecha){

        $result = new \DateTime('now', new \DateTimeZone('Europe/Madrid'));

        date_create_from_format('d/m/Y H:i', $fecha . " 00:00", new \DateTimeZone('Europe/Madrid')) != null ? $result = date_create_from_format('d/m/Y H:i', $fecha . " 00:00", new \DateTimeZone('Europe/Madrid')) : null;
        date_create_from_format('Y-m-d H:i', $fecha . " 00:00", new \DateTimeZone('Europe/Madrid')) != null ? $result = date_create_from_format('Y-m-d H:i', $fecha . " 00:00", new \DateTimeZone('Europe/Madrid')) : null;

        //dump($fecha);
        return $result;
    }

    private function getBegin($view, $current_month, $current_year, $action , $f_inicio){

        //dump('getBegin');
        //dump($action);
        //dump($f_inicio);
        //dump(date_create_from_format('d/m/Y H:i', $f_inicio . " 00:00", new \DateTimeZone('Europe/Madrid')));
        //format f_inicio = d/m/Y
        
        //
        //dump($f_inicio);
        $fecha = $this->normalize($f_inicio);
        switch ($view) {
            case 'semanal':
                
                $action == 'show' ? $begin = new \DateTime('Monday this week', new \DateTimeZone('Europe/Madrid')) : $begin = $fecha->modify('Monday this week');
                break;
            case 'mensual':
                
                $action == 'show' ? $begin = new \DateTime('first day of this month', new \DateTimeZone('Europe/Madrid')) : $begin = $fecha->modify('first day of this month');
                break;    
            case 'anual':
                
                if ( $action == 'show' )
                    $current_month > 8 ?  $begin = new \DateTime('1-9-'.$current_year, new \DateTimeZone('Europe/Madrid')) : $begin = new \DateTime('1-9-'.($current_year-1), new \DateTimeZone('Europe/Madrid')); 
                else
                    //$begin = date_create_from_format('d/m/Y H:i', $f_inicio . " 00:00", new \DateTimeZone('Europe/Madrid'));
                      $begin = $fecha;
                    
                break;
            case 'diaria':
                $action == 'show' ? $begin = new \DateTime('now', new \DateTimeZone('Europe/Madrid')) : $begin = $fecha;
                break;
            default:
                $begin = new \DateTime('now', new \DateTimeZone('Europe/Madrid'));
                break;
        }
        
        return $begin;
    }

    private function getTemplate($view){

        switch ($view) 
        {
            case 'semanal':
                $template = 'sgr_calendarios/viewWeek.html.twig';
                break;
            case 'mensual':
                $template = 'sgr_calendarios/viewMonth.html.twig';
                break;    
            case 'anual':
                $template = 'sgr_calendarios/viewWeek.html.twig';
                break;
            case 'diaria':
            default:
                $template = 'sgr_calendarios/viewDay.html.twig';
                break;
        }

        return $template;
    }

    private function getSelectedTab($tabActive,$sgrEspacios){

        //dump($sgrEspacios);
        //exit;   
        if ($sgrEspacios->isEmpty() == false){
            $tabDefault = $sgrEspacios->first()->getId();
            $tab = '';
            foreach ($sgrEspacios as $espacio) 
                $tabActive == $espacio->getId() ? $tab = $espacio->getId() : null;
        
                $tab != '' ? $result = $tab : $result = $tabDefault;

            return $result;
        }

        return 0;
    }

    private function getNombreEspacios($espacios){

        $listNombreEspacios = array();
        foreach ($espacios as $espacio)
            $listNombreEspacios[] = ucwords( $espacio->getNombre() );
            
        return $listNombreEspacios;
    }

    private function getIdEspacios($espacios){

        $listIdEspacios = array();
        foreach ($espacios as $espacio)
            $listIdEspacios[] = $espacio->getId();
    
        return $listIdEspacios;
    }

    private function filterByActividad($sgrFechasEvento, $actividad){
        
		if ( $actividad && $sgrFechasEvento )
		{
	        $aux = new ArrayCollection($sgrFechasEvento);
       		$aux = $aux->filter(function($item) use ($actividad) {
                        
            	                    return $item->getEvento()->getActividad() == $actividad;
                	            });    
        
       		$sgrFechasEvento = $aux->toArray();
        }

        return $sgrFechasEvento;
    }

    private function filterByTitulacion($sgrFechasEvento, $titulacion){

    	if ( $titulacion && $sgrFechasEvento )
		{
        	$aux = new ArrayCollection($sgrFechasEvento);
        	$aux = $aux->filter(function($item) use($titulacion){
        
            	                    return $item->getEvento()->getTitulacion() == $titulacion; 
                	            });
        
        	$sgrFechasEvento = $aux->toArray();
        }

        return $sgrFechasEvento;
    }

    private function filterByAsignatura($sgrFechasEvento, $asignatura){
		
		if ( $asignatura && $sgrFechasEvento )
		{    	
    		$aux = new ArrayCollection($sgrFechasEvento);
        	$aux = $aux->filter(function($item) use($asignatura){
                            
            	                	return $item->getEvento()->getAsignatura() == $asignatura; 
                	        	});

    		$sgrFechasEvento = $aux->toArray();
    	}

    	return $sgrFechasEvento;
    }

    private function filterByProfesor($sgrFechasEvento, $profesor){

    	if ( $profesor && $sgrFechasEvento )
        {
            $aux = new ArrayCollection($sgrFechasEvento);
            $aux = $aux->filter(function($item) use($profesor){
        	
        		                   return $item->getEvento()->getProfesor() == $profesor; 
                		        });
            $sgrFechasEvento = $aux->toArray();

        }

        return $sgrFechasEvento;
    }

    private function filterByEspaciosIdInSession($sgrEspacios, $espaciosIds){
    
        $result = new ArrayCollection();

        if ( !empty($espaciosIds) )
        {
                        
            $result = ( new ArrayCollection( $sgrEspacios ) )->filter(function($sgrEspacio) use ($espaciosIds) {
                                                                                $idSgrEspacio = $sgrEspacio->getId();
                                                                                return in_array($idSgrEspacio,$espaciosIds);
                                                                            });
        }
        else
        {
            
            $filtros['Espacio'] = [];
            $this->session->set('espacios', []);
            $this->session->set('idEspacios',[]);
        }
        
        if ($result->isEmpty() == false)
            return $result;

        return new ArrayCollection( $sgrEspacios ); 

        //return $sgrEspacios;
    }

    /**
        * @Route("/ajax/setTabActive", name="sgr_calendarios_setTabActive", methods={"GET"})
    */
    public function setTabActive(Request $request, SessionInterface $session){

        $this->session = $session;
        $tab = $request->get('tabActive');
        //return $this->json($request->get('tabActive'));
        
        $this->session->set('tabActive', $tab);

        return $this->json(true);
    }

    public function getCalendarios($sgrEspacios, $sgrFechasEvento){

        $aCalendarios = array();

        foreach ($sgrEspacios as $sgrEspacio){ 
                
                $calendario = new Calendario;
                $calendario->setSgrEspacio($sgrEspacio);
                
                $eventos = new ArrayCollection();
                
                foreach ($sgrFechasEvento as $sgrFechaEvento)
                {
                    if ($sgrFechaEvento->getEvento()->getEspacio() == $sgrEspacio)
                        $calendario->setPeriodsByDay( $sgrFechaEvento->getEvento(), $sgrFechaEvento);
                }
                
                $aCalendarios[] = $calendario;
            }

        return $aCalendarios;
    }

    /**
    * @Route("/evento/new/{view}/{espacioId}/{f_inicio}/{h_inicio}", name="sgr_calendarios_evento_new", methods={"GET","POST"}), defaults={"view": "semanal", "espacioId": "", "f_inicio": "", "h_inicio": ""}
    */
    public function new(Request $request, Evento $evento, SgrEspacioRepository $sgrEspacioRepository, SessionInterface $session, $view = 'diaria', $espacioId = '', $f_inicio = '', $h_inicio = ''): Response
    {
        //eliominar parámetro de entrada SessionInterface $session
        $sgrEvento = new SgrEvento();

        //Set default sgrEspacio
        ( $espacioId != '' && $sgrEspacioRepository->find($espacioId) ) ? $sgrEvento->setEspacio($sgrEspacioRepository->find($espacioId)) : null;
        
        //Set default fInicio
        ( $f_inicio != '' && date_create_from_format('Y-m-d H:i', $f_inicio . " 00:00", new \DateTimeZone('Europe/Madrid')) ) ? $sgrEvento->setFInicio(date_create_from_format('Y-m-d H:i', $f_inicio . " 00:00", new \DateTimeZone('Europe/Madrid'))) : $f_inicio = null;
        
        //Set default fFin
        $f_inicio ? $sgrEvento->setFFin(date_create_from_format('Y-m-d H:i', $f_inicio . " 00:00", new \DateTimeZone('Europe/Madrid'))) :  $f_fin = null;
        
        //Set default hInicio
        ( $h_inicio != '' && date_create_from_format('H:i', $h_inicio, new \DateTimeZone('Europe/Madrid')) ) ?   $sgrEvento->setHinicio( date_create_from_format('H:i', $h_inicio, new \DateTimeZone('Europe/Madrid')) ) : $sgrEvento->setHinicio(date_create_from_format('H:i', "08:30", new \DateTimeZone('Europe/Madrid')));
        
        //Set default hFin
        $sgrEvento->setHfin(date_create_from_format('H:i', "21:30", new \DateTimeZone('Europe/Madrid')));

        //Set dia
        $f_inicio ? $sgrEvento->setDias( [ ( date_create_from_format('Y-m-d H:i', $f_inicio . " 00:00", new \DateTimeZone('Europe/Madrid')) )->format('N')] ) : null;

        $form = $this->createForm(SgrEventoType::class, $sgrEvento);
        $form->handleRequest($request);

        //Session
        //$this->session = $session;

        //Get view at session
        //$view = $this->session->get('view', 'diaria');

        //Get url to back
        //$urlToback = $this->generateUrl('sgr_calendarios_vista', ['view' => $view, 'action' => 'save', 'f_inicio' => $f_inicio]);
        $urlToback = $this->generateUrl('sgr_test_calendarios_vista', ['view' => $view, 'espacioId' => $espacioId ,'action' => 'save', 'f_inicio' => $f_inicio]);
            
        if ($form->isSubmitted() && $form->isValid()) {
            $errors = array();
            $entityManager = $this->getDoctrine()->getManager();
            
            //setUser 
            $sgrEvento->setUser($this->getUser());
            
            //setEstado
            $sgrEvento->setEstado('aprobado');
            
            //setUpdatedAt
            $sgrEvento->setUpdatedAt();

            $evento->setEvento($sgrEvento);
            
            //check valid selected dias
            if ( !$evento->isValidDias() )
                $errors[]['message'] = 'Selección de días no válida';
            
            if ( !empty($errors) )
                    return $this->render('sgr_calendarios/eventoNew.html.twig', [
                        'sgr_evento' => $sgrEvento,
                        'errors' => $errors,
                        'form' => $form->createView(),
                        'urlToBack' =>  $urlToback,
                    ]);
            
            //Check solapamiento
            if ( !$evento->hasSolape()->isEmpty()  )
                return $this->render('sgr_calendarios/eventoNew.html.twig', [
                        'sgr_evento' => $sgrEvento,
                        'solapes' => $evento->hasSolape(),
                        'form' => $form->createView(),
                        'urlToBack' =>  $urlToback,
                ]);
            
            //no errors, no salapamientos
            foreach ($evento->getAllFechas() as $dt) {
                $sgrFechasEvento = new sgrFechasEvento();
                $sgrFechasEvento->setFecha($dt);
                $entityManager->persist($sgrFechasEvento);
                $sgrEvento->addFecha($sgrFechasEvento);
            }

            $entityManager->persist($sgrEvento);
            $entityManager->flush();

            $this->addFlash(
                        'success',
                        'Evento salvado con éxito '
                        );
            return $this->redirectToRoute('sgr_calendarios_vista', ['view' => $view, 'action' => 'save', 'f_inicio' => $sgrEvento->getFInicio()->format('Y-m-d') ]);
        }

        
        return $this->render('sgr_calendarios/eventoNew.html.twig', [
            'sgr_evento' => $sgrEvento,
            'form' => $form->createView(),
            'urlToBack' =>  $urlToback, 
        ]);
    }

    /**
     * @Route("/evento/{id}/edit", name="sgr_calendario_evento_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, SgrEvento $sgrEvento, Evento $evento, SessionInterface $session, $f_inicio = ''): Response
    {
        $form = $this->createForm(SgrEventoType::class, $sgrEvento);
        $form->handleRequest($request);

        //Session
        $this->session = $session;

        //Get view at session
        $view = $this->session->get('view', 'diaria');
        
        //Get url to back
        $urlToback = $this->generateUrl('sgr_calendarios_vista', ['view' => $view, 'action' => 'edit', 'f_inicio' => $f_inicio]);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $errors = array();
            $entityManager = $this->getDoctrine()->getManager();
            
            foreach ($sgrEvento->getFechas() as $fecha) {
                $sgrEvento->removeFecha($fecha);
            }
            
            $evento->setEvento($sgrEvento);
            //check valid selected dias
            if ( !$evento->isValidDias() )
                $errors[]['message'] = 'Selección de días no válida';
            
            if ( !empty($errors) )
                    return $this->render('sgr_calendarios/eventoEdit.html.twig', [
                        'sgr_evento' => $sgrEvento,
                        'errors' => $errors,
                        'form' => $form->createView(),
                        'urlToBack' =>  $urlToback, 
                    ]);    
            
            //No errors
            
            //Si solapamientos
            if ( !$evento->hasSolape()->isEmpty()  )
            
                return $this->render('sgr_calendarios/eventoEdit.html.twig', [
                        'sgr_evento' => $sgrEvento,
                        'solapes' => $evento->hasSolape(),
                        'form' => $form->createView(),
                        'urlToBack' =>  $urlToback, 
                ]);
        
            //no errors, no salapamientos
            foreach ($evento->getAllFechas() as $dt) {
                $sgrFechasEvento = new sgrFechasEvento();
                $sgrFechasEvento->setFecha($dt);
                $entityManager->persist($sgrFechasEvento);
                $sgrEvento->addFecha($sgrFechasEvento);
            }

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                        'success',
                        'Evento salvado con éxito '
                        );

            return $this->redirectToRoute('sgr_calendarios_vista', ['view' => $this->session->get('view', null), 'action' => 'save', 'f_inicio' => $sgrEvento->getFInicio()->format('Y-m-d') ]);
        }

        return $this->render('sgr_calendarios/eventoEdit.html.twig', [
            'sgr_evento' => $sgrEvento,
            'form' => $form->createView(),
            'urlToBack' =>  $urlToback, 
        ]);
    }
    
    /**
     * @Route("/{id}", name="sgr_calendarios_evento_delete", methods={"DELETE"})
     */
    public function delete(Request $request, SgrEvento $sgrEvento, SessionInterface $session): Response
    {
        
        //Session
        $this->session = $session;
        //dump($this->session->get('f_inicio'));
        $f_inicio = ( date_create_from_format('d/m/Y H:i', $this->session->get('f_inicio') . " 00:00", new \DateTimeZone('Europe/Madrid')) )->format('Y-m-d');
        //dump($f_inicio);
        //exit;
        if ($this->isCsrfTokenValid('delete'.$sgrEvento->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($sgrEvento);
            $entityManager->flush();
        }

        return $this->redirectToRoute('sgr_calendarios_vista', ['view' => $this->session->get('view', null), 'action' => 'save', 'f_inicio' => $f_inicio ]);
        //return $this->redirectToRoute('sgr_evento_index');
    }

    /**
        * @Route("/ajax/new/evento", name="sgr_calendarios_new", methods={"GET","POST"})
    */
    public function new_ajax(Request $request, Evento $evento)
    {

        $sgrEvento = new SgrEvento();
        $form = $this->createForm(SgrEventoType::class, $sgrEvento);
        $form->handleRequest($request);
        

        //return $this->json( dump($form->getData()) );
        if ( $form->isSubmitted() )
        {
            if( !$form->isValid() )
            {
                //return $this->json( dump($form->getErrors(true)) );
                //return $this->json( dump($form->getData()) );
                $html = $this->render('sgr_form/_errors.html.twig', [
                            'errors' => $form->getErrors(true),
                        ]);
                return $this->json($html);
            }
            else
            {

                $errors = array();
                $entityManager = $this->getDoctrine()->getManager();
            
                //setUser 
                $sgrEvento->setUser($this->getUser());
                
                //setEstado
                $sgrEvento->setEstado('aprobado');
            
                //setUpdatedAt
                $sgrEvento->setUpdatedAt();
                
                $evento->setEvento($sgrEvento);

                //check valid selected dias
                if ( !$evento->isValidDias() )
                    $errors[]['message'] = 'Selección de días no válida';
            
                //si errors return
                if( !empty($errors) )
                {
                    
                    $html = $this->render('sgr_form/_errors.html.twig', [
                            'errors' => $errors,
                    ]);
                    return $this->json($html);
                }
                
                //No errors
                $evento->setEvento($sgrEvento);
                //Si solapamiento
                if ( !$evento->hasSolape()->isEmpty() )
                {
                    //return $this->json('solape');
                    //return $this->json( 'hola' );
                    $html =  $this->render('sgr_form/_errors.html.twig', [
                                    'solapes' => $evento->hasSolape(),
                    ]);
                    return $this->json($html);
                }
                
                //NO errors, no solapamientos
                foreach ($evento->getAllFechas() as $dt) {
                    //return $this->json('no solape');
                    $sgrFechasEvento = new sgrFechasEvento();
                    $sgrFechasEvento->setFecha($dt);
                    $entityManager->persist($sgrFechasEvento);
                    $sgrEvento->addFecha($sgrFechasEvento);
                }

                $entityManager->persist($sgrEvento);
                $entityManager->flush();
                //return new Response('éxito'); //$this->redirectToRoute('sgr_calendarios_index');
                return $this->json(true);
                //Flash resultado 
                
            }
        }
        
        
        return $this->render('sgr_calendarios/new.html.twig', [
                'sgr_evento' => $sgrEvento,
                'form' => $form->createView(),
                ]);
        //return new Response('');
    }
   
}