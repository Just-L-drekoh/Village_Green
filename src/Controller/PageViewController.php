<?php

namespace App\Controller;

use App\Entity\Address;
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
}
