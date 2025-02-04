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
            ->select('SUM(od.price * od.quantity) AS turnover, sd.ref AS reference, SUM(od.quantity) AS quantity, od.price AS price, u.lastName AS lastName')
            ->join('o.orderDetails', 'od') 
            ->join('od.product', 'p')
            ->join('p.supplier', 'sd')
            ->join('sd.user', 'u')  
            ->where('sd.ref = :ref')
            ->setParameter('ref', $ref)
            ->groupBy('sd.ref, od.price, u.lastName') 
            ->getQuery()
            ->getResult();
    }
    
    public function topProductQuantity(int $year): array
    {
        $startDate = new \DateTime($year.'-01-01');
        $endDate = new \DateTime(($year + 1).'-01-01');
    
        return $this->createQueryBuilder('o')
            ->select('p.ref, p.label, SUM(od.quantity) AS total_quantity, s.ref AS supplier')
            ->join('o.orderDetails', 'od')
            ->join('od.product', 'p')
            ->join('p.supplier', 's')
            ->where('o.date >= :startDate AND o.date < :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->groupBy('p.id')
            ->orderBy('total_quantity', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }


    public function topProduct(int $year): array
    {
        $startDate = new \DateTime($year . '-01-01');
        $endDate = new \DateTime(($year + 1) . '-01-01');
    
        $results = $this->createQueryBuilder('o')
            ->select('o.id AS Commande_ID, o.ref AS Reference_Commande')
            ->addSelect('sp.ref AS Fournisseur')  // Récupérer chaque fournisseur individuellement
            ->addSelect('SUM(od.price * od.quantity) AS Total_Produits')
            ->addSelect('o.total AS Total_Commande')
            ->addSelect('(o.total - SUM(od.price * od.quantity)) AS Marge')
            ->join('o.orderDetails', 'od')
            ->join('od.product', 'p')
            ->join('p.supplier', 'sp')
            ->where('o.date BETWEEN :startDate AND :endDate')
            ->groupBy('o.id, o.ref, sp.ref, o.total') // Ajouter sp.ref pour éviter l'erreur de groupement
            ->orderBy('Marge', 'DESC')
            ->setMaxResults(10)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
            ->getResult();
    
        // Concaténation manuelle des fournisseurs
        $groupedResults = [];
        foreach ($results as $row) {
            $commandeId = $row['Commande_ID'];
    
            if (!isset($groupedResults[$commandeId])) {
                $groupedResults[$commandeId] = [
                    'Commande_ID' => $row['Commande_ID'],
                    'Reference_Commande' => $row['Reference_Commande'],
                    'Fournisseurs' => [],
                    'Total_Produits' => $row['Total_Produits'],
                    'Total_Commande' => $row['Total_Commande'],
                    'Marge' => $row['Marge'],
                ];
            }
    
            $groupedResults[$commandeId]['Fournisseurs'][] = $row['Fournisseur'];
        }
    
        // Transformer le tableau de fournisseurs en une chaîne
        foreach ($groupedResults as &$result) {
            $result['Fournisseurs'] = implode(', ', array_unique($result['Fournisseurs']));
        }
    
        return array_values($groupedResults);
    }
    

    

}
