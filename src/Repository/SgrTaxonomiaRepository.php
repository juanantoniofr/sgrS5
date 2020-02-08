<?php

namespace App\Repository;

use App\Entity\SgrTaxonomia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method SgrTaxonomia|null find($id, $lockMode = null, $lockVersion = null)
 * @method SgrTaxonomia|null findOneBy(array $criteria, array $orderBy = null)
 * @method SgrTaxonomia[]    findAll()
 * @method SgrTaxonomia[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SgrTaxonomiaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SgrTaxonomia::class);
    }

    // /**
    //  * @return SgrTaxonomia[] Returns an array of SgrTaxonomia objects
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
    public function findOneBySomeField($value): ?SgrTaxonomia
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
