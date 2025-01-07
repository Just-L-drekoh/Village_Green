<?php

namespace App\Controller;


use App\Service\order\DeliveryService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class DeliveryController extends AbstractController
{
    #[Route('/delivery', name: 'app_delivery')]
    public function index(Request $request, DeliveryService $deliveryService, SessionInterface $session): Response
    {
        $form = $deliveryService->createDeliveryForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $deliveryService->saveDeliveryData($form->getData());
        }
        dump($session->get('delivery'));


        return $this->render('delivery/ChoiceDelivery.html.twig', [
            'controller_name' => 'DeliveryController',
            'form' => $form->createView(),
        ]);
    }
}
