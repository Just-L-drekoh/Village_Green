<?php

namespace App\Controller;

use Doctrine\ORM\Query\Expr\Math;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class StripeController extends AbstractController
{
   #[Route('/create-checkout-session', name: 'create_checkout_session')]
public function createCheckoutSession(SessionInterface $session): JsonResponse | RedirectResponse
{
    $total = $session->get('ttc', 0); 
    dump($total); 

    try {
        Stripe::setApiKey($this->getParameter('stripe_secret'));

        if (!$this->getUser()) {
            return $this->json(['error' => 'Vous devez vous connecter pour effectuer un paiement.'], 403);
        }

        if (!$this->getParameter('stripe_secret')) {
            return $this->json(['error' => 'La clé secrète Stripe n\'est pas configurée.'], 500);
        }

        if ($total <= 0) {
            return $this->json(['error' => 'Le montant total est invalide.'], 400);
        }


        $lineItems = [
            [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => 'Paiement total du panier',
                        'description' => 'Montant TTC de votre panier',
                    ],
                    'unit_amount' => intval($total * 100), 
                ],
                'quantity' => 1, 
            ]
        ];

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => $this->generateUrl('payment_success', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('payment_cancel', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);

        return $this->redirect($session->url);
    } catch (\Stripe\Exception\ApiErrorException $e) {
        return $this->json(['error' => $e->getMessage()], 400);
    }
}

    #[Route('/payment-success', name: 'payment_success', methods: ['GET'])]
    public function success()
    {
        $this->addFlash('success', 'Paiement effectué avec succès !');
        return $this->redirectToRoute('cart_order');
    }

    #[Route('/payment-cancel', name: 'payment_cancel', methods: ['GET'])]
    public function cancel()
    {
        $this->addFlash('error', 'Paiement annulé !');
        return $this->redirectToRoute('cart_index');
    }
}
