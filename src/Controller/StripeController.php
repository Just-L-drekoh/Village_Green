<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class StripeController extends AbstractController
{
    #[Route('/create-checkout-session', name: 'create_checkout_session')]
    public function createCheckoutSession(): JsonResponse
    {
        try {
            // Initialize Stripe with secret key
            Stripe::setApiKey($this->getParameter('stripe_secret'));

            // Create payment session
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => 'Produit de test',
                        ],
                        'unit_amount' => 1000, // 10€ (in cents)
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => $this->generateUrl('payment_success', [], UrlGeneratorInterface::ABSOLUTE_URL),
                'cancel_url' => $this->generateUrl('payment_cancel', [], UrlGeneratorInterface::ABSOLUTE_URL),
            ]);

            return $this->json(['url' => $session->url]);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    #[Route('/payment-success', name: 'payment_success', methods: ['GET'])]
    public function success(): JsonResponse
    {
        return $this->json([
            'status' => 'success',
            'message' => 'Paiement réussi !'
        ], 200);
    }

    #[Route('/payment-cancel', name: 'payment_cancel', methods: ['GET'])]
    public function cancel(): JsonResponse
    {
        return $this->json([
            'status' => 'cancelled',
            'message' => 'Paiement annulé !'
        ], 200);
    }
}
