<?php

namespace App\Service\order;

use App\Entity\User;
use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Service\SendEmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class OrderService
{
    private EntityManagerInterface $entityManager;

    private SendEmailService $sendEmailService;

    public function __construct(EntityManagerInterface $entityManager, SendEmailService $sendEmailService)
    {
        $this->entityManager = $entityManager;

        $this->sendEmailService = $sendEmailService;
    }

    public function calculateFinalPrice(array $cartDetails): float
    {
        $finalPrice = 0;
    
        foreach ($cartDetails as $item) {
            if (isset($item['details']['final_price'])) {
                $finalPrice += (float)$item['details']['final_price'];
            }
        }
    
        return $finalPrice;
    }
    
    public function createOrder($user , array $cartDetails, string $paymentMethod ) : Order
    {
        
        $order = (new Order())
            ->setUser($user)
            ->setPaymentMethod($paymentMethod)
            ->setRef(uniqid('VG-'))
            ->setType('Commande')
            ->setStatus('En attente de validation')
            ->setPaymentDate(new \DateTimeImmutable())
            ->setPaymentStatus('En attente de paiement')
            ->setDate(new \DateTimeImmutable());
    
        $final_price = $this->calculateFinalPrice($cartDetails);
    
        $order->setTotal($final_price);
    
        

        foreach ($cartDetails as $item) {

            // Doctrine 2
            // $item['product'] = $this->entityManager->merge($item['product']);
            $item['product'] = $this->entityManager->getRepository(\App\Entity\Product::class)->findById($item['product']->getId())[0];
            dump($item);
            $orderDetails = (new OrderDetails())
                ->setOrder($order)
                ->setProduct($item['product'])
                ->setQuantity($item['quantity'])
                ->setPrice($item['details']['price_quantity']);
            dump($orderDetails);
            $this->entityManager->persist($orderDetails);
        }
        $this->entityManager->persist($order);

        $this->entityManager->flush();

        
        return $order;

    }
    


    public function sendOrderConfirmationEmail(User $user, $order, $orderDetails, $cartDetails): void
    {
        
        $this->sendEmailService->send(
            'no-reply@VillageGreen.com',
            $user->getEmail(),
            'Validation de commande',
            'recap',
            [
                'user' => $user,
                'order'=> $order,
                'orderDetails'=>$orderDetails,
                'cartDetails' => $cartDetails
            ]
        );
    }
}
