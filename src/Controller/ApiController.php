<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Constraints\Length;

#[Route('/api', name: 'api_')]
class ApiController extends AbstractController
{
    #[Route('/search', name: 'search', methods: ['GET'])]
    public function SearchProducts(Request $request, ProductRepository $productRepository): Response
    {
        try {
            $query = $request->query->get('q', '');
            $products = $productRepository->searchByLabel($query);

            return $this->json($products, 200, [], ['groups' => 'product:read']);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Impossible de récupérer les produits'], 400);
        }
    }

    #[Route('/dashboard/users', name: 'dashboard_users')]
    public function dashboardUsers(Request $request, UserRepository $userRepository): Response
    {
        try {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
    
            $query = $request->query->get('q', '');
            if (strlen($query) < 3) { 
                return $this->json(['error' => 'The search query must be at least 3 characters long.'], 400);
            }
    
            $users = $userRepository->searchUser($query);
            return $this->json(
                $users,
                context: [AbstractNormalizer::GROUPS => ['user:read']]
            );        } catch (AccessDeniedException $e) {
            return $this->json(['error' => 'Vous n\'avez pas les droits pour accéder à cette page'], 403);
        } catch (\Exception $e) {
            return $this->json(['error' => 'An unexpected error occurred.'.$e], 500);
        }
    }

    #[Route('/dashboard/orders', name : 'dashboard_orders')]
    public function dashboardOrders(Request $request, OrderRepository $orderRepository)
    {
        try {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
            $query = $request->query->get('q', '');
            if (strlen($query)< 3){
                return $this->json(['error'=> 'La recherche doit etre au minimum de 3 caracteres '], 400);
            }

            $orders = $orderRepository->SearchOrder($query);
            return $this->json(
                $orders,
                context: [AbstractNormalizer::GROUPS => ['order:read']]

            );
        } catch (\Exception $e) {
            return $this->json(['error' => 'Une erreur inattendue est survenue'], 500);
        }
    }

    #[Route('/dashboard/turnoverYear', name: 'dashboard_turnoverYear')]
    public function dashboardTurnoverYear(Request $request, OrderRepository $orderRepository)
    {
        try {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
    
            $query = (int) $request->get('q', ''); 
            
            if ($query < 1000 || $query > 9999) {
                return $this->json(['error' => 'La recherche doit être une année à 4 chiffres'], 400);
            }
            
            $turnover = $orderRepository->turnoverYear($query);
            
            return $this->json(
                $turnover,
                200,
                [],
                [AbstractNormalizer::GROUPS => ['order:read']]
            );
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }
    

    #[Route('/dashboard/turnoverSupplier', name: 'dashboard_turnoverSupplier' )]
    public function dashboardTurnoverSupplier(Request $request, OrderRepository $orderRepository)
    {
        try {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');

            $ref = (string) $request->get('q','');

            $turnoverSupplier = $orderRepository->turnoverSupplier($ref);

            return $this->json($turnoverSupplier,200,[]);
        } catch(\Exception $e){
            return $this->json(['error'=> $e->getMessage()],500);
        }
    }

    #[Route('/dashboard/topProductQuantityOrder', name: 'dashboard_topProductOrder')]
    public function dashboardTopProductOrder(Request $request, OrderRepository $orderRepository)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
    
        try {
            $year = (int) $request->query->get('q', 2025);
    
            $topProducts = $orderRepository->topProductQuantity($year);
    
            return $this->json([
                'success' => true,
                'data' => $topProducts,
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    #[Route('/dashboard/topProduct', name: 'dashboard_topProduct')]
    public function topProduct(Request $request, EntityManagerInterface $entityManager)
    {
        try {
            $year = (int) $request->query->get('q', 2025);

            $conn = $entityManager->getConnection();

            $sql = 'CALL GetTopOrdersByYear(:year)';
            $stmt = $conn->prepare($sql);
            $resultSet = $stmt->executeQuery(['year' => $year]);

            $topProducts = $resultSet->fetchAllAssociative();

            return $this->json([
                'success' => true,
                 'data'=> $topProducts
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }


    
    
    
}
