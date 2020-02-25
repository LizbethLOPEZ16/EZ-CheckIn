<?php

namespace App\Repository;

use App\Entity\ParentStudent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ParentStudent|null find($id, $lockMode = null, $lockVersion = null)
 * @method ParentStudent|null findOneBy(array $criteria, array $orderBy = null)
 * @method ParentStudent[]    findAll()
 * @method ParentStudent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParentStudentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ParentStudent::class);
    }

    // /**
    //  * @return ParentStudent[] Returns an array of ParentStudent objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ParentStudent
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
