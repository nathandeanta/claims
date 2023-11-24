<?php

namespace App\Repository;

use App\Entity\TypeFlow;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TypeFlow>
 *
 * @method TypeFlow|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeFlow|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeFlow[]    findAll()
 * @method TypeFlow[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeFlowRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeFlow::class);
    }

//    /**
//     * @return TypeFlow[] Returns an array of TypeFlow objects
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

//    public function findOneBySomeField($value): ?TypeFlow
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
