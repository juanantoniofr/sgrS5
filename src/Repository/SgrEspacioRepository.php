<?php

namespace App\Repository;

use App\Entity\SgrEspacio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method SgrEspacio|null find($id, $lockMode = null, $lockVersion = null)
 * @method SgrEspacio|null findOneBy(array $criteria, array $orderBy = null)
 * @method SgrEspacio[]    findAll()
 * @method SgrEspacio[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SgrEspacioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SgrEspacio::class);
    }


    public function exist(string $espacio)
    {

        if (!$espacio)
            return false;

        $qb = $this->createQueryBuilder('sgr_e')
                ->where('sgr_e.nombre = :espacio')
                ->setParameter('espacio', $espacio);

        $query = $qb->getQuery();

        //dump($query->execute());
        //exit;
        return $query->getOneOrNullResult();//->execute();
    }
    
    /**
     * @param $termino = id del termino 
     * @return sgrEventos[] Returns an array of sgrEvento objects
    */
    public function findByFilters($termino)
    {
        $qb = $this->createQueryBuilder('sgr_e');

        if($termino)
            $qb->andWhere('sgr_e.termino = :termino')
                    ->setParameter('termino', $termino);
        
        $query = $qb->getQuery();
            //->orderBy('s.id', 'ASC')
            //->setMaxResults(10)
        return $query->execute();
    }

    
    public function hasEvento(\DateTime $f_desde, \DateTime $f_hasta, \DateTime $hora = null, string $espacio = null)
    {

        
        $qb = $this->createQueryBuilder('sgr_e')
                ->leftjoin('sgr_e.eventos','eventos')
                ->leftjoin('eventos.fechas','fechas')
                ->where('(eventos.f_inicio <= :f_desde AND eventos.f_fin > :f_desde) OR (eventos.f_inicio > :f_desde AND eventos.f_fin > :f_hasta)')
                //->andWhere('eventos.h_inicio <= :hora AND eventos.h_fin > :hora')
                //->andWhere('fechas.fecha = :fecha') //No definido!!!!!
                //->setParameter('hora', $hora)
                ->setParameter('f_desde', $f_desde)
                ->setParameter('f_hasta', $f_hasta);

        if($hora)
            $qb->andWhere('eventos.h_inicio <= :hora AND eventos.h_fin > :hora')
            ->setParameter('hora', $hora);
                
        if($espacio)
            $qb->andWhere('sgr_e.nombre = :espacio')
                ->setParameter('espacio', $espacio);


        $query = $qb->getQuery();

        return $query->execute();
    }
    
    
    // /**
    //  * @return SgrEspacio[] Returns an array of SgrEspacio objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SgrEspacio
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
