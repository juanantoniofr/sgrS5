<?php

namespace App\Repository;

use App\Entity\SgrFechasEvento;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method SgrFechasEvento|null find($id, $lockMode = null, $lockVersion = null)
 * @method SgrFechasEvento|null findOneBy(array $criteria, array $orderBy = null)
 * @method SgrFechasEvento[]    findAll()
 * @method SgrFechasEvento[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SgrFechasEventoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SgrFechasEvento::class);
    }


    public function findFechasWithOutEventoId(Array $aDateTime,$excludeEventoId = null){
        //dump($aDateTime);
        
        $qb = $this->createQueryBuilder('sgr_fe')
            ->where('sgr_fe.fecha IN (:aDateTime)')
            ->setParameter('aDateTime', $aDateTime);

        if($excludeEventoId)
            $qb->andWhere('sgr_fe.evento != :id')
                ->setParameter('id',$excludeEventoId);

        $query = $qb->getQuery();

        return $query->execute();
    }

    /**
     * @return SgrFechasEvento[] Returns an array of SgrFechasEvento objects
    */
    public function findByFecha($fecha)
    {
        $qb = $this->createQueryBuilder('sgr_fe')
            ->andWhere('sgr_fe.fecha = :fecha')
            ->setParameter('fecha', $fecha->format('Y-m-d'));
        
        $query = $qb->getQuery();
            //->orderBy('s.id', 'ASC')
            //->setMaxResults(10)
        return $query->execute();
    }


    public function findBetween($begin, $end){
        //dump($begin->format('Y-m-d'));
        //dump($end->format('Y-m-d'));
        $qb = $this->createQueryBuilder('sgr_fe')
                ->andWhere('sgr_fe.fecha >= :begin')
                ->andWhere('sgr_fe.fecha <= :end')
                ->setParameter('begin',$begin->format('Y-m-d'))
                ->setParameter('end',$end->format('Y-m-d'));

        $query = $qb->getQuery();
        //dump($query);
        return $query->execute();
    }
}
