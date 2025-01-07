<?php

namespace App\Service\order;

use App\Entity\DeliveryDetails;
use App\Form\ChoiceDeliveryType;
use App\Repository\ProductRepository; // Assuming the repository for products
use Doctrine\ORM\EntityManagerInterface; // Assuming you're using Doctrine ORM
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class DeliveryService
{
    private FormFactoryInterface $formFactory;
    private RequestStack $requestStack;
    private ProductRepository $productRepository;  // Added missing product repository
    private EntityManagerInterface $entityManager; // Added missing entity manager

    public function __construct(
        FormFactoryInterface $formFactory,
        RequestStack $requestStack,
        ProductRepository $productRepository, // Injected ProductRepository
        EntityManagerInterface $entityManager  // Injected EntityManager
    ) {
        $this->formFactory = $formFactory;
        $this->requestStack = $requestStack;
        $this->productRepository = $productRepository;
        $this->entityManager = $entityManager;
    }

    public function getProductsFromCart(): array
    {
        return $this->requestStack->getSession()->get('panier', []);
    }

    public function createDeliveryForm(?array $data = null): \Symfony\Component\Form\FormInterface
    {
        $products = $this->getProductsFromCart();

        return $this->formFactory->create(ChoiceDeliveryType::class, $data, [
            'products' => $products,
        ]);
    }

    public function saveDeliveryData(array $data): void
    {
        $this->requestStack->getSession()->set('delivery', $data);

        foreach ($data as $productId => $quantity) {
            $product = $this->productRepository->find($productId);
            $delivery = $this->requestStack->getSession()->get('delivery');
            if (!$product) {
                continue;
            }

            $deliveryDetails = new DeliveryDetails();
            $deliveryDetails->setProduct($product);
            $deliveryDetails->setDelivery($delivery); // Assuming you have a $delivery variable
            $deliveryDetails->setShippedQty($quantity);

            $this->entityManager->persist($deliveryDetails);
        }


        $this->entityManager->flush();
    }
}
