<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

//use Symfony\Component\Form\Extension\Core\Type\FileType;
//use Symfony\Component\HttpFoundation\File\UploadedFile;

//use App\Service\Csv;
//use App\Service\Evento;

/*use App\Entity\SgrEspacio;
use App\Entity\SgrEvento;
use App\Entity\sgrFechasEvento;
use App\Entity\SgrProfesor;
use App\Entity\SgrAsignatura;
use App\Entity\SgrTitulacion;
use App\Entity\SgrTipoActividad;
use App\Entity\SgrGrupoAsignatura;
*/

use App\Form\SgrSearchSgrEspacioType;
use App\Repository\SgrFechasEventoRepository;
use App\Repository\SgrEspacioRepository;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/admin/sgr/search")
 */
class SgrSearchController extends AbstractController
{
    /**
     * @Route("/index/{page}", name="sgr_search_index", defaults={"page"=1}, methods={"GET","POST"})
     */
    public function index(Request $request, SgrEspacioRepository $sgrEspacioRepository, SgrFechasEventoRepository $sgrFechasEventoRepository, PaginatorInterface $paginator, $page): Response
    {

        $form = $this->createForm(SgrSearchSgrEspacioType::class);
        $form->handleRequest($request);

        /*$sgrEspacios = new ArrayCollection($sgrEspacioRepository->findAll());
        $aSolapes = new ArrayCollection();
        foreach ($sgrEspacios as $sgrEspacio) {
            $aSolapes->set($sgrEspacio->getId(), new ArrayCollection( array( $sgrEspacio, 'solapes' => new ArrayCollection()  ) ));
        }
        */
        if ($form->isSubmitted() && $form->isValid()) 
        {

            $data = $form->getData();

            $termino = '';
            if($data['termino'])
                $termino = $data['termino'];
            
            //Search by termino
            $sgrEspacios = new ArrayCollection($sgrEspacioRepository->findByFilters($termino));
            $aSolapes = new ArrayCollection();    
            foreach ($sgrEspacios as $sgrEspacio) 
            {
                //add all sgrEspacios
                $aSolapes->set($sgrEspacio->getId(), new ArrayCollection( array( $sgrEspacio, 'solapes' => new ArrayCollection()  ) ));
                
                //Search by equipamiento
                //$data['equipamiento'] -> será un array
                $sgrEspacio->getMediosDisponibles()->initialize();
                if( $data['equipamiento'] && false == $sgrEspacio->getMediosDisponibles()->contains($data['equipamiento']) )
                    //if not contains equipamiento => remove(sgrEspacio)
                    $aSolapes->remove($sgrEspacio->getId());

                //search by aforo
                if($data['aforo'])
                    if( $sgrEspacio->getAforo() < $data['aforo'])
                        //if aforo sgrEspacio < aforo form  => remove(sgrEspacio)
                        $aSolapes->remove($sgrEspacio->getId());    

                //Search by aforoExamen
                if($data['aforoExamen'])
                    if ( $sgrEspacio->getAforoExamen() < $data['aforoExamen'] )
                        //if aforoExamen sgrEspacio < aforoExamen form  => remove(sgrEspacio)
                        $aSolapes->remove($sgrEspacio->getId());
            }
            
            //search by aforo
            if($data['aforo'])
                if( $sgrEspacio->getAforo() < $data['aforo'])
                    $aSolapes->remove($sgrEspacio->getId());

            //search by f_inicio && f_fin && h_inicio && h_fin
            $f_inicio = new \DateTimeZone('Europe/Madrid');
            if ($data['f_inicio'])
                $f_inicio = clone date_create_from_format('d/m/Y', $data['f_inicio'], new \DateTimeZone('Europe/Madrid'));//->getId();
            
            $f_fin = new \DateTimeZone('Europe/Madrid');
            if (!$data['f_fin'])
                //Si no existe, hacemos f_fin igual a f_inicio
                $f_fin = clone $f_inicio;
            else 
                $f_fin = clone date_create_from_format('d/m/Y', $data['f_fin'], new \DateTimeZone('Europe/Madrid'));
            
            if ( $f_inicio->diff($f_fin)->format('%a') <= 0 )
                //si la diferencia es negativa o cero, f_fin <= f_inicio => f_fin = f_inicio +7 days 
                $f_fin->modify('+7 days');
            
            $interval = new \DateInterval('P7D');
            $rangeDates = new \DatePeriod($f_inicio, $interval, $f_fin);
                        
            foreach ($rangeDates as $date)
            {
                $solapes = $sgrFechasEventoRepository->findByFecha($date);
                if ( $solapes )
                {
                    foreach ( $solapes as $solape ) {

                        $solapaHoras = false;
                        
                        if ( (new \DateTime($data['h_inicio']))->format('H:i') >= $solape->getEvento()->getHInicio()->format('H:i') && (new \DateTime($data['h_inicio']))->format('H:i') < $solape->getEvento()->getHFin()->format('H:i') )
                        {
                            $solapaHoras = true;
                        }
                        if ( (new \DateTime($data['h_inicio']))->format('H:i') < $solape->getEvento()->getHInicio()->format('H:i') && (new \DateTime($data['h_fin']))->format('H:i') > $solape->getEvento()->getHInicio()->format('H:i') )
                        {
                            $solapaHoras = true;
                        }
                        $solapaHoras = true;
                        if( $solapaHoras )
                        {
                            $sgrEspacio = $solape->getEvento()->getEspacio();
                            if($aSolapes->containsKey($sgrEspacio->getId()))
                                $aSolapes->get($sgrEspacio->getId())->get('solapes')->add($solape);    
                        }
                        
                    }
                }
            }
            //dump($data['h_inicio']);
            //dump($aSolapes);
            //exit;

            $pagination = $paginator->paginate(
                            //$sgrEspacios,
                            $aSolapes,
                            $page,//$request->query->getInt('page', 1),
                            10
                            );

            return $this->render('sgr_search/index.html.twig', [
                            'pagination' => $pagination,
                            'form'       => $form->createView(),
                            ]);
        }

        return $this->render('sgr_search/index.html.twig', [
            //'sgr_eventos'   => $sgrEventos,
            //'pagination' => $pagination,
            'form'       => $form->createView(),
        ]);
    }

        
}
