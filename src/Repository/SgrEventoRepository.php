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
    
    public function getSgrEventosByFilters($id_titulacion, $curso, $id_asignatura, $id_profesor, \DateTime $f_inicio, \DateTime $f_fin, $id_espacio, $id_actividad){

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

        if($f_inicio)
            $qb->andWhere('sgr_e.f_inicio > :finicio')
                ->setParameter('finicio', $f_inicio->format('Y-m-d'));

        if($f_fin)
            $qb->andWhere('sgr_e.f_fin < :ffin')
                ->setParameter('ffin', $f_fin->format('Y-m-d'));

        if($id_espacio)
            $qb->andWhere('sgr_e.espacio = :id_espacio')
            ->setParameter('id_espacio', $id_espacio);

        if($id_actividad)
            $qb->andWhere('sgr_e.actividad = :id_actividad')
            ->setParameter('id_actividad', $id_actividad);


        $query = $qb->getQuery();
        //dump($query);
        //exit;
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
