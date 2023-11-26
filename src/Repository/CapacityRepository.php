<?php

namespace App\Repository;

use App\Entity\Capacity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Capacity>
 *
 * @method Capacity|null find($id, $lockMode = null, $lockVersion = null)
 * @method Capacity|null findOneBy(array $criteria, array $orderBy = null)
 * @method Capacity[]    findAll()
 * @method Capacity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CapacityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Capacity::class);
    }

//    /**
//     * @return Capacity[] Returns an array of Capacity objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Capacity
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
