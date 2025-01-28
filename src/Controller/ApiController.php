<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
    
}
