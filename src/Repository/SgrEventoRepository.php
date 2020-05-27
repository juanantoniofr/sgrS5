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
    
    //public function getSgrEventosByFilters($id_titulacion, $curso, $id_asignatura, $id_profesor, \DateTime $f_inicio, \DateTime $f_fin, $id_espacio, $id_actividad){
    public function getSgrEventosByFilters($id_titulacion, $curso, $id_asignatura, $id_profesor, \DateTime $f_inicio, \DateTime $f_fin, $espacios, $id_actividad){

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

        /*if($id_espacio)
            $qb->andWhere('sgr_e.espacio = :id_espacio')
            ->setParameter('id_espacio', $id_espacio);*/
        if($espacios)
            $qb->andWhere('sgr_e.espacio IN (:espacios)')
             ->setParameter('espacios', $espacios);

        if($id_actividad)
            $qb->andWhere('sgr_e.actividad = :id_actividad')
            ->setParameter('id_actividad', $id_actividad);


        $query = $qb->getQuery();
        //dump($query);
        //exit;
        return $query->execute();
    }

    public function getByFilters($titulacion, $actividad, \DateTime $begin, \DateTime $end, $espacios)//, $curso, $id_asignatura, $id_profesor,  $id_actividad)
    {

        $qb = $this->createQueryBuilder('sgr_e');
        //$qb->addSelect('DATE_DIFF(sgr_e.h_fin,sgr_e.h_inicio) as duracionHoras');

        if($titulacion)
            $qb->andWhere('sgr_e.titulacion = :titulacion')
                ->setParameter('titulacion', $titulacion);

        //if($id_asignatura)
        //    $qb->andWhere('sgr_e.asignatura = :id_asignatura')
        //        ->setParameter('id_asignatura', $id_asignatura);

        //if($id_profesor)
        //    $qb->andWhere('sgr_e.profesor = :id_profesor')
        //        ->setParameter('id_profesor', $id_profesor);

        
        if($begin && $end)
            $qb->andWhere('sgr_e.f_inicio >= :begin')
                ->andWhere('sgr_e.f_inicio < :end')
                ->orWhere('sgr_e.f_inicio < :begin AND sgr_e.f_fin > :begin')
                ->setParameter('begin', $begin->format('Y-m-d'))
                ->setParameter('end', $end->format('Y-m-d'));

        if($espacios)
            $qb->andWhere('sgr_e.espacio IN (:espacios)')
             ->setParameter('espacios', $espacios);

        /*if ($termino)
            $qb->leftJoin('sgr_e.espacio', 'espacios')
                ->andWhere('espacios.termino = :termino')
                ->setParameter('termino',$termino);
        */
        if($actividad)
            $qb->andWhere('sgr_e.actividad = :actividad')
                ->setParameter('actividad', $actividad);



        $qb->orderBy('sgr_e.f_inicio', 'ASC');
        $qb->orderBy('sgr_e.h_inicio', 'ASC');
        $qb->addOrderBy('sgr_e.h_fin', 'DESC');
        //
        //$qb->orderBy('sgr_e.h_inicio','ASC');
        $query = $qb->getQuery();
        return $query->execute();
    }
    
    public function findAllOrderByUpdateAt(){

        $qb = $this->createQueryBuilder('sgr_e')
                ->orderBy('sgr_e.updatedAt', 'DESC');

        $query = $qb->getQuery();
            
            //->setMaxResults(10)
        return $query->execute();
    }

    public function findAllbetween(\DateTime $f_inicio, \DateTime $f_fin){

        $qb = $this->createQueryBuilder('sgr_e');
                

        $qb->andWhere('sgr_e.f_inicio > :finicio')
                ->setParameter('finicio', $f_inicio->format('Y-m-d'));

        $qb->andWhere('sgr_e.f_fin < :ffin')
                ->setParameter('ffin', $f_fin->format('Y-m-d'));
        
        $qb->orderBy('sgr_e.updatedAt', 'DESC');
 
        $query = $qb->getQuery();
            
        return $query->execute();
    }
}
