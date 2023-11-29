<?php

namespace App\Repository;

use App\Entity\Theft;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Theft>
 *
 * @method Theft|null find($id, $lockMode = null, $lockVersion = null)
 * @method Theft|null findOneBy(array $criteria, array $orderBy = null)
 * @method Theft[]    findAll()
 * @method Theft[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TheftRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Theft::class);
    }

//    /**
//     * @return Theft[] Returns an array of Theft objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Theft
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
