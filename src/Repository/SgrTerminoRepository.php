<?php

namespace App\Repository;

use App\Entity\SgrTermino;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method SgrTermino|null find($id, $lockMode = null, $lockVersion = null)
 * @method SgrTermino|null findOneBy(array $criteria, array $orderBy = null)
 * @method SgrTermino[]    findAll()
 * @method SgrTermino[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SgrTerminoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SgrTermino::class);
    }

    // /**
    //  * @return SgrTermino[] Returns an array of SgrTermino objects
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
    public function findOneBySomeField($value): ?SgrTermino
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
