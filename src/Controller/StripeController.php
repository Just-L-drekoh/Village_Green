<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class StripeController extends AbstractController
{
    #[Route('/create-checkout-session', name: 'create_checkout_session')]
    public function createCheckoutSession(): JsonResponse | RedirectResponse
    {
        try {
            Stripe::setApiKey($this->getParameter('stripe_secret'));

            if (!$this->getUser()) {
                return $this->json(['error' => 'Vous devez vous connecter pour effectuer un paiement.'], 403);
            }

            if (!$this->getParameter('stripe_secret')) {
                return $this->json(['error' => 'La clé secrète Stripe n\'est pas configurée.'], 500);
            }

            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => 'Produit de test',
                        ],
                        'unit_amount' => 1000,
                    ],
                    'quantity' => 1,
                ]],
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
        return $this->render('stripe/success.html.twig', [
            'message' => 'Paiement effectué avec succès !',
            'status' => 'success'
        ]);
    }


    #[Route('/payment-cancel', name: 'payment_cancel', methods: ['GET'])]
    public function cancel()
    {

        return $this->render('stripe/cancel.html.twig', [
            'message' => 'Paiement annulé !',
            'status' => 'error'
        ]);
    }
}
