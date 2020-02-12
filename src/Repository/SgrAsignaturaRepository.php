<?php

namespace App\Repository;

use App\Entity\SgrAsignatura;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method SgrAsignatura|null find($id, $lockMode = null, $lockVersion = null)
 * @method SgrAsignatura|null findOneBy(array $criteria, array $orderBy = null)
 * @method SgrAsignatura[]    findAll()
 * @method SgrAsignatura[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SgrAsignaturaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SgrAsignatura::class);
    }

    // /**
    //  * @return SgrAsignatura[] Returns an array of SgrAsignatura objects
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
    public function findOneBySomeField($value): ?SgrAsignatura
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
