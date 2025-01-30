<?php

namespace App\Repository;

use App\Entity\Order;
use App\Entity\OrderDetails;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Order>
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    //    /**
    //     * @return Order[] Returns an array of Order objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('o.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Order
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function searchOrder($query): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.ref LIKE :query')
            ->setParameter('query', '%'.$query.'%')
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    public function turnoverYear($query): array
    {
        $conn = $this->getEntityManager()->getConnection();
        
        $sql = '
            SELECT MONTH(o.date) as month, SUM(o.total) as chiffreAffaire
            FROM `order` o
            WHERE YEAR(o.date) = :year
            GROUP BY month
            ORDER BY month ASC
        ';
        
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['year' => $query]);
        
        return $resultSet->fetchAllAssociative();
    }

    public function turnoverSupplier(int $supplierId): array
    {
        return $this->createQueryBuilder('o')
            ->select('o.total AS turnover, sd.ref AS reference')
            ->join('o.orderDetails', 'od') 
            ->join('od.product', 'p')
            ->join('p.supplier', 'sd') 
            ->where('p.supplier = :supplierId')
            ->setParameter('supplierId', $supplierId)
            ->getQuery()
            ->getResult();
    }
    
    

}
