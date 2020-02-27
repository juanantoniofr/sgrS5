<?php

namespace App\Repository;

use App\Entity\SgrFechasEvento;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method SgrFechasEvento|null find($id, $lockMode = null, $lockVersion = null)
 * @method SgrFechasEvento|null findOneBy(array $criteria, array $orderBy = null)
 * @method SgrFechasEvento[]    findAll()
 * @method SgrFechasEvento[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SgrFechasEventoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SgrFechasEvento::class);
    }

    public function exists(\Date $fecha){

        $qb = $this->createQueryBuilder('sgr_fe')
                ->where('sgr_fe.fecha = :fecha')
                ->setParameter('fecha', $fecha);

        $query = $qb->getQuery();

        return $query->execute();
    }

    public function finByFechas(Array $aDateTime,$excludeFechasByIdEvento){
        dump($aDateTime);
        $qb = $this->createQueryBuilder('sgr_fe')
            ->where('sgr_fe.fecha IN (:aDateTime)')
            ->setParameter('aDateTime', $aDateTime);

        if($excludeFechasByIdEvento)
            $qb->andWhere('sgr_fe.evento != :id')
                ->setParameter('id',$excludeFechasByIdEvento);

        $query = $qb->getQuery();
        //dump($query);
        //exit;

        return $query->execute();
    }

    // /**
    //  * @return SgrFechasEvento[] Returns an array of SgrFechasEvento objects
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
    public function findOneBySomeField($value): ?SgrFechasEvento
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
