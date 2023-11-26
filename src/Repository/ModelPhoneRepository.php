<?php

namespace App\Repository;

use App\Entity\ModelPhone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ModelPhone>
 *
 * @method ModelPhone|null find($id, $lockMode = null, $lockVersion = null)
 * @method ModelPhone|null findOneBy(array $criteria, array $orderBy = null)
 * @method ModelPhone[]    findAll()
 * @method ModelPhone[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModelPhoneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ModelPhone::class);
    }

//    /**
//     * @return ModelPhone[] Returns an array of ModelPhone objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ModelPhone
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
