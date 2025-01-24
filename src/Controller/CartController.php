<?php

namespace App\Controller;

use App\Entity\OrderDetails;
use App\Entity\Product;
use App\Service\order\OrderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/cart', name: 'cart_')]
class CartController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private OrderService $orderService
        
    ) {}

    private function calculateProductDetails(Product $product, int $quantity): array
    {
        if ($quantity <= 0) {
            throw new \InvalidArgumentException('La quantité doit être supérieure à 0');
        }

        $priceQuantity = $product->getPrice() * $quantity;
        $taxRate = $product->getTax()->getRate() / 100;
        $priceWithTax = $priceQuantity * (1 + $taxRate);
        
        $user = $this->getUser();
        if (!$user) {
            throw new \RuntimeException('Utilisateur non connecté');
        }
        
        $userCoef = 1.05;
        $finalPrice = $priceWithTax * $userCoef;
        
        return [
            'price_per_unit' => $product->getPrice(),
            'quantity' => $quantity,
            'price_quantity' => $priceQuantity,
            'tax_rate' => $taxRate,
            'price_with_tax' => $priceWithTax,
            'user_coef' => $userCoef,
            'final_price' => $finalPrice,
        ];
    }

    private function calculateCartTotal(array $cartDetails): array
    {
        $total = 0;
        $totalWithoutTax = 0;
        $totalTax = 0;

        foreach ($cartDetails as $item) {
            $details = $item['details'];
            $total += $details['final_price'];
            $totalWithoutTax += $details['price_quantity'];
            $totalTax += ($details['price_with_tax'] - $details['price_quantity']);
        }

        return [
            'total' => $total,
            'total_without_tax' => $totalWithoutTax,
            'total_tax' => $totalTax
        ];
    }

    #[Route('/', name: 'index')]
    public function index(SessionInterface $session): Response
    {
        $shoppingCart = $session->get('shoppingCart', []);
        $cartDetails = [];

        foreach ($shoppingCart as $productId => $quantity) {
            $product = $this->entityManager->getRepository(Product::class)->find($productId);
            if ($product) {
                $cartDetails[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'details' => $this->calculateProductDetails($product, $quantity)
                ];
            }
        }
        dump($cartDetails);
        $session->set('cartDetails', $cartDetails);
        $totals = $this->calculateCartTotal($cartDetails);
        
        return $this->render('cart/index.html.twig', [
            'cartDetails' => $cartDetails,
            'totals' => $totals
        ]);
    }


    #[Route('/add/{id}', name: 'add')]
    public function add(Product $product, SessionInterface $session): Response
    {
        $shoppingCart = $session->get('shoppingCart', []);
        $productId = $product->getId();

        if (!isset($shoppingCart[$productId])) {
            $shoppingCart[$productId] = 0;
        }
        
        $shoppingCart[$productId]++;
        $session->set('shoppingCart', $shoppingCart);

        $this->addFlash('success', 'Produit ajouté au panier');
        
        return $this->redirectToRoute('cart_index');
    }

    #[Route('/remove/{id}', name: 'remove')]
    public function remove(Product $product, SessionInterface $session): Response
    {
        $shoppingCart = $session->get('shoppingCart', []);
        $productId = $product->getId();

        if (isset($shoppingCart[$productId])) {
            if ($shoppingCart[$productId] > 1) {
                $shoppingCart[$productId]--;
            } else {
                unset($shoppingCart[$productId]);
            }
            
            $session->set('shoppingCart', $shoppingCart);
        }

        return $this->redirectToRoute('cart_index');
    }

    #[Route('/clear', name: 'clear')]
    public function clear(SessionInterface $session): Response
    {
        $session->remove('shoppingCart');
        
        return $this->redirectToRoute('cart_index');
    }

    #[Route('/checkout', name: 'checkout')]
    public function checkout(SessionInterface $session)
    {
        $cartDetails=$session->get('cartDetails');
        dump($cartDetails);

        return $this->render('cart/recap.html.twig', [
            'cartDetails'=> $cartDetails
        ]);
    }

    #[Route('/validation', name: 'validation')]
    public function validation(SessionInterface $session)
    {
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('error', 'Vous devez être connecté pour valider votre commande.');
            return $this->redirectToRoute('app_login');
        }
    
        $cartDetails = $session->get('cartDetails');
        dump($cartDetails);
        if (!$cartDetails || empty($cartDetails)) {
            $this->addFlash('error', 'Votre panier est vide.');

            return $this->redirectToRoute('cart_index');
        }
    

        $paymentMethod = $session->get('paymentMethod');

        if (!$paymentMethod) {

            $this->addFlash('error', 'Veuillez choisir un mode de paiement.');
            return $this->redirectToRoute('cart_payment');
        }
        
          $order = $this->orderService->createOrder($user, $cartDetails, $paymentMethod);
          $orderDetails = $this->entityManager->getRepository(OrderDetails::class)->findBy(['order' => $order]);
          $this->orderService->sendOrderConfirmationEmail($user, $order, $orderDetails, $cartDetails);
    
            $this->addFlash('success', 'Votre commande a été validée.');
            $session->remove('shoppingCart'); 
            $session->remove('paymentMethod');
       
    
        return $this->redirectToRoute('VillageGreen_index');
    }
    

}