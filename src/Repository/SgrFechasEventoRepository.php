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

        $qb->createQueryBuilder('sgr_fe')
                ->where('sgr_fe.fecha = :fecha')
                ->setParameter('fecha', $fecha);

        $qb->getQuery();

        return $qb->execute();
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
