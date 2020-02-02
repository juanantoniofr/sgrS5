<?php

namespace App\Repository;

use App\Entity\SgrTaxonomiaEspacio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method SgrTaxonomiaEspacio|null find($id, $lockMode = null, $lockVersion = null)
 * @method SgrTaxonomiaEspacio|null findOneBy(array $criteria, array $orderBy = null)
 * @method SgrTaxonomiaEspacio[]    findAll()
 * @method SgrTaxonomiaEspacio[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SgrTaxonomiaEspacioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SgrTaxonomiaEspacio::class);
    }

    // /**
    //  * @return SgrTaxonomiaEspacio[] Returns an array of SgrTaxonomiaEspacio objects
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
    public function findOneBySomeField($value): ?SgrTaxonomiaEspacio
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
