<?php

namespace App\Form;

use App\Repository\ProductRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChoiceDeliveryType extends AbstractType
{
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $products = $options['products'] ?? [];

        foreach ($products as $productId => $quantity) {
            // Récupérer le produit depuis la base de données
            $product = $this->productRepository->find($productId);

            if (!$product) {
                continue; // Skip si le produit n'existe pas
            }

            $builder
                ->add('product_' . $productId, IntegerType::class, [
                    'label' => $product->getLabel(), // Ou toute autre propriété que vous utilisez pour le label
                    'data' => $quantity,
                    'required' => false,
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'products' => [],
        ]);

        $resolver->setAllowedTypes('products', 'array');
    }
}
