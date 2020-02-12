<?php

namespace App\Repository;

use App\Entity\SgrProfesor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method SgrProfesor|null find($id, $lockMode = null, $lockVersion = null)
 * @method SgrProfesor|null findOneBy(array $criteria, array $orderBy = null)
 * @method SgrProfesor[]    findAll()
 * @method SgrProfesor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SgrProfesorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SgrProfesor::class);
    }

    // /**
    //  * @return SgrProfesor[] Returns an array of SgrProfesor objects
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
    public function findOneBySomeField($value): ?SgrProfesor
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
