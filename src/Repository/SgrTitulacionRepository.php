<?php

namespace App\Repository;

use App\Entity\SgrTitulacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method SgrTitulacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method SgrTitulacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method SgrTitulacion[]    findAll()
 * @method SgrTitulacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SgrTitulacionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SgrTitulacion::class);
    }

    // /**
    //  * @return SgrTitulacion[] Returns an array of SgrTitulacion objects
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
    public function findOneBySomeField($value): ?SgrTitulacion
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
