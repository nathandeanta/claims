<?php

namespace App\Repository;

use App\Entity\TypeRoofing;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TypeRoofing>
 *
 * @method TypeRoofing|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeRoofing|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeRoofing[]    findAll()
 * @method TypeRoofing[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeRoofingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeRoofing::class);
    }

//    /**
//     * @return TypeRoofing[] Returns an array of TypeRoofing objects
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

//    public function findOneBySomeField($value): ?TypeRoofing
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
