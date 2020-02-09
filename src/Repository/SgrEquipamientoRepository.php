<?php

namespace App\Repository;

use App\Entity\SgrEquipamiento;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method SgrEquipamiento|null find($id, $lockMode = null, $lockVersion = null)
 * @method SgrEquipamiento|null findOneBy(array $criteria, array $orderBy = null)
 * @method SgrEquipamiento[]    findAll()
 * @method SgrEquipamiento[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SgrEquipamientoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SgrEquipamiento::class);
    }

    // /**
    //  * @return SgrEquipamiento[] Returns an array of SgrEquipamiento objects
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
    public function findOneBySomeField($value): ?SgrEquipamiento
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
