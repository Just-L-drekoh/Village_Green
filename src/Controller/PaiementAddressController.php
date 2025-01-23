<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\OrderType;
use App\Form\BankCartType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route("/cart/validation", name: "validation_cart_")]

class PaiementAddressController extends AbstractController
{

    #[Route("/address", name: "address")]

    public function chooseAddress(SessionInterface $session, EntityManagerInterface $entityManager): Response
    {
        try {
            $cart = $session->get('shoppingCart', []);

            if(!$this->getUser())
            {
                $this->addFlash('error', 'Vous devez Vous Connectez pour continuer');
                return $this->redirectToRoute('app_login');
            }
            if (empty($cart)) {
                $this->addFlash('warning', 'Votre panier est vide');
                return $this->redirectToRoute('cart_index');
            }

            $user = $this->getUser();
            if (!$user) {
                $this->addFlash('error', 'Utilisateur non authentifié.');
                return $this->redirectToRoute('app_login');
            }

            $addresses = $entityManager->getRepository(Address::class)->findBy(['user' => $user]);
            $session->set('user', $user);
            $session->set('address', $addresses);
        } catch (\Exception $e) {
            $this->addFlash('error', 'Une erreur est survenue , reessayer plus tard.');
            return $this->redirectToRoute('cart_index');
        }
        return $this->render('address/order/Choice_address.html.twig', [
            'cart' => $cart,
            'user' => $user,
            'addresses' => $addresses,
        ]);
    }


    #[Route("/paiement", name: "paiement")]

    public function handlePaiement(SessionInterface $session, Request $request): Response
    {
        try {
            $user = $this->getUser();
            if (!$user) {
                $this->addFlash('error', 'Utilisateur non authentifié.');
                return $this->redirectToRoute('app_login');
            }

            $formPaiementMethod = $this->createForm(OrderType::class, null, ['user' => $user]);
            $formPaiementMethod->handleRequest($request);

            if ($formPaiementMethod->isSubmitted() && $formPaiementMethod->isValid()) {
                $this->processPaiementMethodForm($formPaiementMethod, $session);
            }
            dump($session->get('paymentMethod'));
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('cart_index');
        }
        return $this->render('address/order/Choice_paiement.html.twig', [
            'formPaiementMethod' => $formPaiementMethod->createView(),

        ]);
    }

    private function processPaiementMethodForm($form, SessionInterface $session): void
    {

        $session->set('paymentMethod', $form->get('paiement')->getData());
    }
}
