<?php

namespace App\Repository;

use App\Entity\Order;
use App\Entity\OrderDetails;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\Constraint\StringContains;

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

    public function turnoverSupplier(string $ref): array
    {
        return $this->createQueryBuilder('o')
            ->select('(od.price * od.quantity) AS turnover, sd.ref AS reference, od.quantity AS quantity, od.price AS price, u.lastName AS lastName')
            ->join('o.orderDetails', 'od') 
            ->join('od.product', 'p')
            ->join('p.supplier', 'sd')
            ->join('sd.user', 'u')  // Ensure this relationship exists
            ->where('sd.ref = :ref')
            ->setParameter('ref', $ref)
            ->getQuery()
            ->getResult();
    }
    
    
    

}
