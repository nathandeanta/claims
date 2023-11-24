<?php

namespace App\Repository;

use App\Entity\InvestmentEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InvestmentEntity>
 *
 * @method InvestmentEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method InvestmentEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method InvestmentEntity[]    findAll()
 * @method InvestmentEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvestmentEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InvestmentEntity::class);
    }

//    /**
//     * @return InvestmentEntity[] Returns an array of InvestmentEntity objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?InvestmentEntity
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function filter( $bank,  $search,  $start,  $end, $type )
    {
        $queryBuilder = $this->createQueryBuilder('i');

        $where =true;

        if(!empty($bank)) {

            if($where) {
                $where = false;

                $queryBuilder->Where('i.bank = :bank');

            }else{

                $queryBuilder->andWhere('i.bank = :bank');
            }

            $queryBuilder->setParameter('bank', $bank);
        }

        if(!empty($search)) {

            if($where) {
                $where = false;

                $queryBuilder->Where('i.description LiKe  :description');

            }else{

                $queryBuilder->andWhere('i.description LiKe  :description');
            }

            $queryBuilder->setParameter('description', '%' . $search . '%');
        }

        if(!empty($type)) {

            if($where) {
                $where = false;

                $queryBuilder->Where('i.type LiKe  :type');

            }else{

                $queryBuilder->andWhere('i.type LiKe  :type');
            }

            $queryBuilder->setParameter('type', '%' . $type . '%');
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
