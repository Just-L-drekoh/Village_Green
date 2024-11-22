<?php

namespace App\Controller;

use App\Entity\Rubric;
use App\Entity\Address;
use App\Entity\Product;
use App\Form\AddressFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PageViewController extends AbstractController
{
    
    public function index(): Response
    {
     

        return $this->render('page_view/index.html.twig', [
            
        ]);
    }

    public function products(EntityManagerInterface $entityManager): Response
    {
        $products = $entityManager->getRepository(Product::class)->findAll();
        

        return $this->render('page_view/products.html.twig', [
            'products' => $products
        ]);
    }

    public function rubriques(EntityManagerInterface $entityManager): Response
    {
        $rubrics = $entityManager->getRepository(Rubric::class)->findAll();
        

        return $this->render('page_view/rubrics.html.twig', [
            'rubrics' => $rubrics
        ]);
    }
}
