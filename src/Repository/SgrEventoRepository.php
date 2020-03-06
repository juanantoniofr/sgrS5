<?php

namespace App\Repository;

use App\Entity\SgrEvento;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method SgrEvento|null find($id, $lockMode = null, $lockVersion = null)
 * @method SgrEvento|null findOneBy(array $criteria, array $orderBy = null)
 * @method SgrEvento[]    findAll()
 * @method SgrEvento[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SgrEventoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SgrEvento::class);
    }
    
    public function getSgrEventosByFilters($id_titulacion, $curso, $id_asignatura, $id_profesor){

        $qb = $this->createQueryBuilder('sgr_e');

        if($id_titulacion)
            $qb->andWhere('sgr_e.titulacion = :id_titulacion')
                ->setParameter('id_titulacion', $id_titulacion);

        //if($curso)

        if($id_asignatura)
            $qb->andWhere('sgr_e.asignatura = :id_asignatura')
                ->setParameter('id_asignatura', $id_asignatura);

        if($id_profesor)
            $qb->andWhere('sgr_e.profesor = :id_profesor')
                ->setParameter('id_profesor', $id_profesor);

        $query = $qb->getQuery();

        return $query->execute();
    }
    // /**
    //  * @return SgrEvento[] Returns an array of SgrEvento objects
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
    public function findOneBySomeField($value): ?SgrEvento
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
