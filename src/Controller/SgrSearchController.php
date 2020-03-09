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

        $sgrEspacios = new ArrayCollection($sgrEspacioRepository->findAll());

        $aSolapes = new ArrayCollection();
        
        foreach ($sgrEspacios as $sgrEspacio) {
            $aSolapes->set($sgrEspacio->getId(), new ArrayCollection( array( $sgrEspacio, 'solapes' => new ArrayCollection()  ) ));
        }
        
        

        if ($form->isSubmitted() && $form->isValid()) 
        {

            $data = $form->getData();

            $termino = '';
            if($data['termino'])
                $termino = $data['termino'];
            
            $sgrEspacios = new ArrayCollection($sgrEspacioRepository->findByFilters($termino));
            $aSolapes = new ArrayCollection();    
            foreach ($sgrEspacios as $sgrEspacio) 
            {
                
                $aSolapes->set($sgrEspacio->getId(), new ArrayCollection( array( $sgrEspacio, 'solapes' => new ArrayCollection()  ) ));
                //$data['equipamiento'] -> será un array
                $sgrEspacio->getMediosDisponibles()->initialize();
                if($data['equipamiento'] && false == $sgrEspacio->getMediosDisponibles()->contains($data['equipamiento']))
                    $aSolapes->remove($sgrEspacio->getId());
            }
            
            $f_inicio = '';
            if ($data['f_inicio'])
                $f_inicio = date_create_from_format('d-m-Y', $data['f_inicio'], new \DateTimeZone('Europe/Madrid'));//->getId();
            
            $f_fin = '';
            if ($data['f_fin'])
                $f_fin = date_create_from_format('d-m-Y', $data['f_fin'], new \DateTimeZone('Europe/Madrid'));//$data['f_fin'];//->getId();

            
            $interval = new \DateInterval('P7D');
            $rangeDates = new \DatePeriod($f_inicio, $interval, $f_fin);
                        
            foreach ($rangeDates as $date)
            {
                $solapes = $sgrFechasEventoRepository->findByFecha($date);
                if ($solapes )
                {
                    foreach ($solapes as $solape) {
                        $sgrEspacio = $solape->getEvento()->getEspacio();
                        if($aSolapes->containsKey($sgrEspacio->getId()))
                            $aSolapes->get($sgrEspacio->getId())->get('solapes')->add($solape);
                    }
                }

            }
        }

        $pagination = $paginator->paginate(
            //$sgrEspacios,
            $aSolapes,
            $page,//$request->query->getInt('page', 1),
            10
        );

        return $this->render('sgr_search/index.html.twig', [
            //'sgr_eventos'   => $sgrEventos,
            'pagination' => $pagination,
            'form'       => $form->createView(),
        ]);
    }

        
}
