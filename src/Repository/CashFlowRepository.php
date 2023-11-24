<?php

namespace App\Repository;

use App\Entity\CashFlow;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CashFlow>
 *
 * @method CashFlow|null find($id, $lockMode = null, $lockVersion = null)
 * @method CashFlow|null findOneBy(array $criteria, array $orderBy = null)
 * @method CashFlow[]    findAll()
 * @method CashFlow[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CashFlowRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CashFlow::class);
    }

    public function getTotalAmountForBankAndCurrency($bank, $currencyTo, $currency)
    {
        return $this->createQueryBuilder('c')
            ->select('SUM(c.current_convert) as total')
            ->where('c.bank = :bank')
            ->andWhere('c.currency_to = :currencyTo')
            ->andWhere('c.currency = :currency')
            ->setParameter('bank', $bank)
            ->setParameter('currencyTo', $currencyTo)
            ->setParameter('currency', $currency)
            ->getQuery()
            ->getSingleScalarResult();
    }


    public function filter($bank, $search, $start, $end, $status, $currency, $category)
    {
        $queryBuilder = $this->createQueryBuilder('c')
                    ->where('c.currency= :currency')
                    ->setParameter('currency', $currency);

        if(!empty($bank)) {
            $queryBuilder->andWhere('c.bank = :bank');
            $queryBuilder->setParameter('bank', $bank);
        }

        if(!empty($search)) {
            $queryBuilder->andWhere('c.description LIKE :search');
            $queryBuilder->setParameter('search', '%' . $search . '%');
        }

        if(!empty($status)) {
            $queryBuilder->andWhere('c.type = :status');
            $queryBuilder->setParameter('status', $status);
        }

        if (!empty($category)) {
            $queryBuilder->join('c.typeFlow', 'tf');
            $queryBuilder->andWhere('tf.id_type_flow = :id_type'); // Use 'id_type_flow' instead of 'id'
            $queryBuilder->setParameter('id_type', $category);
        }

        if(!empty($start)) {
            $queryBuilder->andWhere('c.date BETWEEN :start AND :end');
            $queryBuilder->setParameter('start', $start);
            $queryBuilder->setParameter('end', $end);
        }

        $queryBuilder->orderBy('c.date', 'DESC');
        return $queryBuilder->getQuery()->getResult();

    }

    public function getTotalAmountForBankAndCurrencyFilter($bank, $currencyTo, $search, $start, $end, $status, $currency, $category)
    {

        $queryBuilder = $this->createQueryBuilder('c')
            ->select('SUM(c.current_convert) as total')
            ->where('c.currency_to = :currencyTo')
            ->andWhere("c.currency = :currency")
            ->setParameter('currencyTo', $currencyTo)
            ->setParameter('currency', $currency);

        if (!empty($bank)) {
            $queryBuilder->andWhere('c.bank = :bank_param');
            $queryBuilder->setParameter('bank_param', $bank);
        }

        if (!empty($search)) {
            $queryBuilder->andWhere('c.description LIKE :search_param');
            $queryBuilder->setParameter('search_param', '%' . $search . '%');
        }

        if(!empty($status)) {
            $queryBuilder->andWhere('c.type = :status');
            $queryBuilder->setParameter('status', $status);
        }

        if (!empty($category)) {
            $queryBuilder->join('c.typeFlow', 'tf');
            $queryBuilder->andWhere('tf.id_type_flow = :id_type'); // Use 'id_type_flow' instead of 'id'
            $queryBuilder->setParameter('id_type', $category);
        }

        if (!empty($start)) {
            $queryBuilder->andWhere('c.date BETWEEN :start_param AND :end_param');
            $queryBuilder->setParameter('start_param', $start);
            $queryBuilder->setParameter('end_param', $end);
        }

        return $queryBuilder->getQuery()->getResult();

    }

//    /**
//     * @return CashFlow[] Returns an array of CashFlow objects
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

//    public function findOneBySomeField($value): ?CashFlow
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

}
