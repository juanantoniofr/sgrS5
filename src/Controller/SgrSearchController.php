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
//use App\Repository\SgrEventoRepository;
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
    public function index(Request $request, SgrEspacioRepository $sgrEspacioRepository, PaginatorInterface $paginator, $page): Response
    {

        $form = $this->createForm(SgrSearchSgrEspacioType::class);
        $form->handleRequest($request);

        $sgrEspacios = $sgrEspacioRepository->findAll();

        if ($form->isSubmitted() && $form->isValid()) 
        {

            $data = $form->getData();

            $f_inicio = '';
            if ($data['f_inicio'])
                $f_inicio = date_create_from_format('d-m-Y', $data['f_inicio'], new \DateTimeZone('Europe/Madrid'));//->getId();
            
            $f_fin = '';
            if ($data['f_fin'])
                $f_fin = date_create_from_format('d-m-Y', $data['f_fin'], new \DateTimeZone('Europe/Madrid'));//$data['f_fin'];//->getId();


            $sgrEspacios = $sgrEspacios->filter(funtion($sgrEspacio) use ($f_inicio,$f_fin){

                    
            });
            

            //$sgrEspacioRepository->hasEvento($f_inicio, $f_fin);

        }

        $pagination = $paginator->paginate(
            $sgrEspacios,
            $page,//$request->query->getInt('page', 1),
            5
        );

        return $this->render('sgr_search/index.html.twig', [
            //'sgr_eventos'   => $sgrEventos,
            'pagination' => $pagination,
            'form'       => $form->createView(),
        ]);
    }

        
}
