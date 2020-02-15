<?php

namespace App\Repository;

use App\Entity\SgrTipoActividad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method SgrTipoActividad|null find($id, $lockMode = null, $lockVersion = null)
 * @method SgrTipoActividad|null findOneBy(array $criteria, array $orderBy = null)
 * @method SgrTipoActividad[]    findAll()
 * @method SgrTipoActividad[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SgrTipoActividadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SgrTipoActividad::class);
    }

    // /**
    //  * @return SgrTipoActividad[] Returns an array of SgrTipoActividad objects
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
    public function findOneBySomeField($value): ?SgrTipoActividad
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
