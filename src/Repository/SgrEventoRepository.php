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


    public function getEventosBetween(\DateTime $f_inicio, \DateTime $f_fin, sgrEspacio $sgrEspacio = null){


        $qb = $this->createQueryBuilder('sgr_e')
                        ->where('sgr_e.f_inicio >= :f_inicio AND sgr_e.f_inicio <= :f_fin')
                        ->orWhere('sgr_e.f_fin >= :f_inicio AND sgr_e.f_fin <= :f_fin')
                        ->setParameter('f_inicio', $f_inicio)
                        ->setParameter('f_fin', $f_fin);

        if ($sgrEspacio)
            $qb->andWhere('sgr_e.espacio_id = :id')
                ->setParameter('id', $sgrEspacio->getId());
        
        $query = $qb->getQuery();

        return $query->execute();
    }

    public function getEventosContains(\DateTime $fecha, $espacio_id = null){

        $qb = $this->createQueryBuilder('e')
                        ->where('e.f_inicio <= :fecha AND e.f_fin >= :fecha')
                        ->setParameter('fecha', $fecha);

        if ($espacio_id)
            $qb->andWhere('e.espacio = :espacio_id')
                ->setParameter('espacio_id', $espacio_id);
        
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
