<?php

namespace App\Repository;

use App\Entity\SgrGrupoAsignatura;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method SgrGrupoAsignatura|null find($id, $lockMode = null, $lockVersion = null)
 * @method SgrGrupoAsignatura|null findOneBy(array $criteria, array $orderBy = null)
 * @method SgrGrupoAsignatura[]    findAll()
 * @method SgrGrupoAsignatura[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SgrGrupoAsignaturaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SgrGrupoAsignatura::class);
    }

    // /**
    //  * @return SgrGrupoAsignatura[] Returns an array of SgrGrupoAsignatura objects
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
    public function findOneBySomeField($value): ?SgrGrupoAsignatura
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
