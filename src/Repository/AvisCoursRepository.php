<?php

namespace App\Repository;

use App\Entity\AvisCours;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AvisCours|null find($id, $lockMode = null, $lockVersion = null)
 * @method AvisCours|null findOneBy(array $criteria, array $orderBy = null)
 * @method AvisCours[]    findAll()
 * @method AvisCours[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AvisCoursRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AvisCours::class);
    }

    // /**
    //  * @return AvisCours[] Returns an array of AvisCours objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AvisCours
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
