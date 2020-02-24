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
