<?php

namespace App\Repository;

use App\Entity\Debts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Debts>
 *
 * @method Debts|null find($id, $lockMode = null, $lockVersion = null)
 * @method Debts|null findOneBy(array $criteria, array $orderBy = null)
 * @method Debts[]    findAll()
 * @method Debts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DebtsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Debts::class);
    }

//    /**
//     * @return Debts[] Returns an array of Debts objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Debts
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function filter($service,  $search,  $start,  $end)
    {
        $queryBuilder = $this->createQueryBuilder('i');

        $where =true;

        if(!empty($service)) {

            if($where) {
                $where = false;

                $queryBuilder->Where('i.service = :service');

            }else{

                $queryBuilder->andWhere('i.service = :service');
            }

            $queryBuilder->setParameter('service', $service);
        }

        if(!empty($search)) {

            if($where) {
                $where = false;

                $queryBuilder->Where('i.describe LiKe  :description');

            }else{

                $queryBuilder->andWhere('i.describe LiKe  :description');
            }

            $queryBuilder->setParameter('description', '%' . $search . '%');
        }


        if(!empty($start)) {

            if($where){
                $where = false;

                $queryBuilder->Where('i.date BETWEEN :start AND :end');

            }else{
                $queryBuilder->andWhere('i.date BETWEEN :start AND :end');

            }

            $queryBuilder->setParameter('start', $start);
            $queryBuilder->setParameter('end', $end);

        }

        $queryBuilder->orderBy('i.date', 'DESC');
        return $queryBuilder->getQuery()->getResult();
    }
}
