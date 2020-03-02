<?php

namespace App\Repository;

use App\Entity\SgrProfesor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method SgrProfesor|null find($id, $lockMode = null, $lockVersion = null)
 * @method SgrProfesor|null findOneBy(array $criteria, array $orderBy = null)
 * @method SgrProfesor[]    findAll()
 * @method SgrProfesor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SgrProfesorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SgrProfesor::class);
    }

    /**
      * @return SgrProfesor[] Returns an array of SgrProfesor objects
    */
    public function findByNombre(string $nombre)
    {
        return $this->createQueryBuilder('sgr_p')
            ->where('sgr_p.nombre = :nombre')
            ->setParameter('nombre', $nombre)
            ->getQuery()
            ->getResult();
    }
    

    /*
    public function findOneBySomeField($value): ?SgrProfesor
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
